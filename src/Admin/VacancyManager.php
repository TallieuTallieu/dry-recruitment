<?php

namespace Tnt\Recruitment\Admin;

use app\container\Application;
use dry\admin\component\BooleanEdit;
use dry\admin\component\BooleanView;
use dry\admin\component\I18nSwitcher;
use dry\admin\component\RichtextEdit2;
use dry\admin\component\SortHandle;
use dry\admin\component\Stack;
use dry\admin\component\StringEdit;
use dry\admin\component\StringView;
use dry\admin\Module;
use dry\media\Picker;
use dry\media\Thumbnail;
use dry\orm\action\Create;
use dry\orm\action\Delete;
use dry\orm\action\Edit;
use dry\orm\component\InlineBooleanEdit;
use dry\orm\component\Pagination;
use dry\orm\Index;
use dry\orm\Manager;
use dry\orm\paginate\Paginator;
use dry\orm\sort\DragSorter;
use Oak\Contracts\Config\RepositoryInterface;
use Tnt\Blog\Model\BlogPostBlock;
use Tnt\Recruitment\Model\Vacancy;
use dry\admin\component\TabbedContent;
use dry\admin\component\EnumSwitcher;

class VacancyManager extends Manager
{
    public function __construct(array $kwargs = [])
    {
        $app = Application::get();
        $config = $app->get(RepositoryInterface::class);

        $languages = [];
        $requiredLanguages = [];

        extract($kwargs, EXTR_IF_EXISTS);

        parent::__construct(Vacancy::class, [
            'icon' => Module::ICON_ASSIGNMENT,
            'plural' => 'vacancies',
        ]);

        $generalComponents = [];
        $contentComponents = [];
        $videoComponents = [];

        foreach ($languages as $language) {
            $generalComponents[$language] = [
                new StringEdit('title_'.$language, [
                    'v8n_required' => in_array($language, $requiredLanguages),
                    'suggest_slug' => 'slug_'.$language,
                    'label' => 'title',
                ]),
                new StringEdit('slug_'.$language, [
                    'v8n_required' => in_array($language, $requiredLanguages),
                    'handle_duplicate' => TRUE,
                    'slugify_on_blur' => TRUE,
                    'label' => 'slug',
                ]),
                new StringEdit('introduction_'.$language, [
                    'multiline' => TRUE,
                    'height' => 120,
                    'label' => 'introduction',
                ]),
            ];

            $contentComponents[$language] = [
                new StringEdit('short_description_'.$language, [
                    'multiline' => TRUE,
                    'height' => 120,
                    'label' => 'short description',
                ]),
                new RichtextEdit2('challenge_'.$language, [
                    'label' => 'challenge',
                ]),
                new RichtextEdit2('profile_'.$language, [
                    'label' => 'profile',
                ]),
                new RichtextEdit2('offer_'.$language, [
                    'label' => 'offer',
                ]),
                new RichtextEdit2('contact_'.$language, [
                    'label' => 'contact',
                ]),
            ];

            if ($config->get('recruitment.allow_video', false)) {
                $videoTypes = $this->getVideoTypes($language, $config->get('recruitment.video_types', []));

                $videoComponents[$language] = [
                    new EnumSwitcher('video_type', $videoTypes),
                ];
            }
        }

        $generalComponentsContainer = new Stack(Stack::VERTICAL, $generalComponents[$languages[0]]);
        $contentComponentsContainer = new Stack(Stack::VERTICAL, $contentComponents[$languages[0]]);

        if ($config->get('recruitment.allow_video', false)) {
            $videoComponentsContainer = new Stack(Stack::VERTICAL, $videoComponents[$languages[0]]);
        }

        if (count($languages) > 1) {
            $generalComponentsContainer = new I18nSwitcher($generalComponents);
            $contentComponentsContainer = new I18nSwitcher($contentComponents);

            if ($config->get('recruitment.allow_video', false)) {
                $videoComponentsContainer = new I18nSwitcher($videoComponents);
            }
        }

        $this->actions[] = $create = new Create([
            $generalComponentsContainer,
            new Picker('photo', [
                'v8n_required' => TRUE,
                'v8n_mimetype' => [
                    'image/jpeg',
                    'image/png',
                ],
            ]),
        ], [
            'mode' => Create::MODE_POPUP,
        ]);

        $tabbedContent = [
            ['General', [
                $contentComponentsContainer,
            ]],
        ];

        if ($config->get('recruitment.allow_video', false)) {
            $tabbedContent[] = ['Video', [
                $videoComponentsContainer,
            ]];
        }

        $this->actions[] = $edit = new Edit([
            new Stack(Stack::HORIZONTAL, [
                new TabbedContent($tabbedContent),
                new Stack(Stack::VERTICAL, [
                    new Stack(Stack::VERTICAL, $create->components),
                    new BooleanEdit('is_featured'),
                    new BooleanEdit('is_visible'),
                ], [
                    'title' => 'General information'
                ]),
            ], [
                'grid' => [5, 2],
            ]),
        ], [
            'fixed_footer' => TRUE,
        ]);

        $this->actions[] = $delete = new Delete();

        $this->header[] = $create->create_link('Add vacancy');
        $this->footer[] = new Pagination();

        $this->index = new Index( [
            new SortHandle(),
            new Thumbnail('photo'),
            new StringView('title_nl', [
                'style' => StringView::STYLE_TITLE,
                'header' => 'Title',
            ]),
            new InlineBooleanEdit('is_featured', [
                'header' => 'Is Featured'
            ]),
            $edit->create_link(),
            $delete->create_link(),
        ], [
            'field_to_row_class' => [ 'is_visible', NULL, \dry\orm\IndexRow::STYLE_DISABLED, ],
        ] );

        $this->index->sorter = new DragSorter('sort_index');

        $this->index->paginator = new Paginator(10);
    }

    private function getVideoTypes(string $language, array $types): array
    {
        $videoTypes = [
            'file' => [Vacancy::VIDEO_TYPE_FILE, 'File', [
                new Picker('video', [
                    'v8n_required' => false,
                    'v8n_mimetype' => [
                        'video/mp4'
                    ],
                ]),
                new Picker('video_thumb', ['label' => 'video thumbnail']),
                new StringEdit('media_credit_' . $language, ['label' => 'video credit']),
            ]],
            'vimeo' => [Vacancy::VIDEO_TYPE_VIMEO, 'Vimeo', [
                new StringEdit('video_id', [
                    'v8n_required' => true
                ]),
                new Picker('video_thumb', ['label' => 'video thumbnail']),
                new StringEdit('media_credit_' . $language, ['label' => 'video credit']),
            ]],
            'youtube' => [Vacancy::VIDEO_TYPE_YOUTUBE, 'Youtube', [
                new StringEdit('video_id', [
                    'v8n_required' => true
                ]),
                new Picker('video_thumb', ['label' => 'video thumbnail']),
                new StringEdit('media_credit_' . $language, ['label' => 'video credit']),
            ]],
        ];

        return array_filter($videoTypes, function ($type, $key) use ($types) {
            return in_array($key, $types);
        }, ARRAY_FILTER_USE_BOTH);
    }
}
