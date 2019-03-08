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
```http
GET /admin/api/pages
```
<a name="pages-create"></a>
### Create page
```http
POST /admin/api/pages
```
<a name="pages-get"></a>
### Get page
```http
GET /admin/api/pages/{id}
```
<a name="pages-update"></a>
### Update page
```http
PATCH /admin/api/pages/{id}
```
<a name="pages-delete"></a>
### Delete page
```http
DELETE /admin/api/pages/{id}
```
<a name="templates-all"></a>
### List template
```http
GET /admin/api/page-templates
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
