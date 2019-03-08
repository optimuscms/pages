# Pages
This package provides the core backend functionality for creating pages and page templates 
within the CMS.

## Installation
This package can be installed through Composer.

`composer require optimuscms/pages`

In Laravel 5.5 and above the package will autoregister the service provider. 

In Laravel 5.4 you must install this service provider:
```php
// config/app.php
'providers' => [
    ...
    Optimus\Pages\PageServiceProvider::class,
    ...
];
```

## API Routes

The API follows standard RESTful conventions, with responses being returned in JSON. 
Appropriate HTTP status codes are provided, and these should be used to check the outcome of an operation.

**Pages**
 - [List pages](#pages-all)
 - [Get page](#pages-get)
 - [Create page](#pages-create)
 - [Update page](#pages-update)
 - [Delete page](#pages-delete)
 
**Templates**
 - [List templates](#templates-all)

<a name="pages-all"></a>
### List pages
List all current pages.
```http
GET /admin/api/pages
```

**Parameters**

| Parameter | Required? | Type  | Description    |
|-----------|-----------|-------|----------------|
| parent    |    ✗      | int   | A page ID. When provided will only show pages that have this page as their parent. |


**Example Response**

```json5
[
    {
        "id": 19,
        "title": "Top 10 ways to increase your online exposure",
        "slug": "online-exposure",
        "uri": "marketing/online-exposure",
        "has_fixed_uri": true,
        "parent_id": 17,
        "template": "blog-post",
        "has_fixed_template": true,
        "contents": [
            {
                "id": 26,
                "key": "intro",
                "value": "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.",
                "created_at": "2019-02-19 09:36:23",
                "updated_at": "2019-02-19 09:36:23"
            },
            {
                "id": 27,
                "key": "body",
                "value": "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                "created_at": "2019-02-19 09:36:23",
                "updated_at": "2019-02-19 09:36:23"
            }
        ],
        "media": [
            {
                "id": 356,
                "folder_id": 12, 
                "name": "My Image", 
                "file_name": "my_image.jpg",
                "disk": "local",
                "mime_type": "image/jpeg", 
                "size": 102400,
                "created_at": "2017-12-24 09:36:23",
                "updated_at": "2017-12-25 10:15:12"
            },
            {
                "id": 513,
                "folder_id": 4, 
                "name": "Landscape", 
                "file_name": "landscape.png",
                "disk": "local",
                "mime_type": "image/png", 
                "size": 219462,
                "created_at": "2019-02-19 09:36:23",
                "updated_at": "2019-02-19 09:36:23"
            }
        ],
        "children_count": 3,
        "is_stand_alone": false,
        "is_published": true,
        "is_deletable": true,
        "created_at": "2019-02-19 09:36:23",
        "updated_at": "2019-02-19 09:36:23"
    },
    {
        // ...details of second page
    }
]
```


<a name="pages-create"></a>
### Create page
Create and store a new page.
```http
POST /admin/api/pages
```

**Parameters**

| Parameter | Required? | Type  | Description    |
|-----------|-----------|-------|----------------|
| title     |    ✓      | string  | The page title |
| template  |    ✓      | string  | The name of the template that should be applied to this page |
| is_stand_alone |    ✓ | boolean | If false, the page will not appear in any navigation and will only be accessible via a direct link. |
| is_published |    ✓   | boolean | Whether the page is ready to be made public |
| parent_id | ✗         | int     | The ID of a page to nest this one under |
| slug      | ✗         | string  | A URL-friendly identifier. Will be used as part of the final public-facing URL to the page. |


**Example Response**

Returns the newly created page. See [single page response example](#single-page-response-example).


<a name="pages-get"></a>
### Get page
Get details of a specific page.
```http
GET /admin/api/pages/{id}
```

**Parameters**

| Parameter | Required? | Type  | Description    |
|-----------|-----------|-------|----------------|
| id      |    ✓      | int  | The ID of the page |

<a name="single-page-response-example"></a>
**Example Response**

```json
{
    "id": 19,
    "title": "Top 10 ways to increase your online exposure",
    "slug": "online-exposure",
    "uri": "marketing/online-exposure",
    "has_fixed_uri": true,
    "parent_id": 17,
    "template": "blog-post",
    "has_fixed_template": true,
    "contents": [
        {
            "id": 26,
            "key": "intro",
            "value": "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.",
            "created_at": "2019-02-19 09:36:23",
            "updated_at": "2019-02-19 09:36:23"
        },
        {
            "id": 27,
            "key": "body",
            "value": "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
            "created_at": "2019-02-19 09:36:23",
            "updated_at": "2019-02-19 09:36:23"
        }
    ],
    "media": [
        {
            "id": 356,
            "folder_id": 12, 
            "name": "My Image", 
            "file_name": "my_image.jpg",
            "disk": "local",
            "mime_type": "image/jpeg", 
            "size": 102400,
            "created_at": "2017-12-24 09:36:23",
            "updated_at": "2017-12-25 10:15:12"
        },
        {
            "id": 513,
            "folder_id": 4, 
            "name": "Landscape", 
            "file_name": "landscape.png",
            "disk": "local",
            "mime_type": "image/png", 
            "size": 219462,
            "created_at": "2019-02-19 09:36:23",
            "updated_at": "2019-02-19 09:36:23"
        }
    ],
    "children_count": 3,
    "is_stand_alone": false,
    "is_published": true,
    "is_deletable": true,
    "created_at": "2019-02-19 09:36:23",
    "updated_at": "2019-02-19 09:36:23"
}
```


<a name="pages-update"></a>
### Update page
Update the details of a specific page.
```http
PATCH /admin/api/pages/{id}
```

**Parameters**

| Parameter | Required? | Type  | Description    |
|-----------|-----------|-------|----------------|
| title     |    ✓      | string  | The page title |
| template  |    ✓      | string  | The name of the template that should be applied to this page |
| is_stand_alone |    ✓ | boolean | If false, the page will not appear in any navigation and will only be accessible via a direct link. |
| is_published |    ✓   | boolean | Whether the page is ready to be made public |
| parent_id | ✗         | int     | The ID of a page to nest this one under |
| slug      | ✗         | string  | A URL-friendly identifier. Will be used as part of the final public-facing URL to the page. |


**Example Response**

Returns the updated page. See [single page response example](#single-page-response-example).

<a name="pages-delete"></a>
### Delete page
Delete a specific page.
```http
DELETE /admin/api/pages/{id}
```

**Parameters**

| Parameter | Required? | Type  | Description    |
|-----------|-----------|-------|----------------|
| id      |    ✓      | int  | The ID of the page |

**Example Response**

The HTTP status code will be 204 if successful.

<a name="templates-all"></a>
### List template
List all available templates.
```http
GET /admin/api/page-templates
```

**Parameters**

None

**Example Response**

```json
[
    {
        "name": "blog-post", 
        "label": "Blog Post"
    },
    {
        "name": "special-offer", 
        "label": "Special Offer"
    }
]
```

### Working with page templates

#### Create a template
```php
use Optimus\Pages\Template;
use Illuminate\Http\Request;
use Optimus\Pages\Models\Page;

class DefaultTemplate extends Template
{
    public function name(): string
    {
        return 'default';
    }
    
    public function label(): string
    {
        return 'A custom label';
    }

    public function validate(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);
    }

    public function save(Page $page, Request $request)
    {
        $page->addContents([
            'content' => $request->input('content')
        ]);

        $page->attachMedia($request->input('media_id'));
    }
}
```

#### Register and retrieve templates
```php
use Optimus\Pages\Facades\Template;

// Get all the registered templates...
Template::all();

// Get the template with the given name...
Template::find($name);

// Register a template...
Template::register(new DefaultTemplate);

// Register multiple templates...
Template::registerMany([
    new HomeTemplate,
    new ContactTemplate
]);
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
