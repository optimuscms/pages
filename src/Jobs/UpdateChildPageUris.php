<?php

namespace Optimus\Pages\Jobs;

use Illuminate\Bus\Queueable;
use Optimus\Pages\Models\Page;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateChildPageUris implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $page;

    /**
     * Create a new job instance.
     *
     * @param  Page  $page
     * @return void
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->page->descendants->map(function (Page $page) {
            $page->uri = $page->getUri();
            $page->save();
        });
    }
}
