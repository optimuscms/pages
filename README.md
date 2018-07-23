# Optimus Pages

## Installation

```bash
composer require optimuscms/pages
```

## Usage

### Templates

```bash
php artisan make:template HomeTemplate
```

```php
<?php

namespace App\Templates;

use Optimus\Pages\Templates\Template;

class HomeTemplate extends Template
{   
    public function rules()
    {
        return [
            'foo' => 'required',
            'bar' => 'required'
        ];
    }
    
    public function save()
    {
        $this->page->addContents(
            $this->contents->only(['foo', 'bar'])
        );
    }
}
```

### Displaying pages

```php
Route::get('blog', 'PostsController@index');
```

```php
<?php

namespace App\Http\Controllers\Front;

use Optimus\Pages\Page;
use Optimus\Posts\Post;

class PostsController extends Controller
{
    public function index(Page $page)
    {
        $posts = Post::all();
        
        return $page->template->handler->render([
            'posts' => $posts    
        ]);
    }
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.