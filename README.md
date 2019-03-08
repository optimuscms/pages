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
 - [Get folder](#pages-get)
 - [Create folder](#pages-create)
 - [Update folder](#pages-update)
 - [Delete folder](#pages-delete)
 
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

**Example Response**



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



<a name="templates-all"></a>
### List template
List all available templates.
```http
GET /admin/api/page-templates
```

**Parameters**

None

**Example Response**



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
