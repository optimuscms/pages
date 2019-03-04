# Pages

## Usage

### Api

Todo...

### Templates

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

    public function selectable(): bool
    {
        return false;
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

// Get all the selectable templates...
Template::selectable();

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
