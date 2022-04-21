<?php
namespace seisvalt\widgets;

use yii\base\Widget;
use kartik\export\ExportMenu;
use kartik\grid\GridView as KGrid;
use yii\helpers\Html;
use Yii;

class GridView extends Widget
{
    public $dataProvider;
    public $gridColumns;
    public $searchModel;
    public $pdfConfig;
    public $createButton;
    private $panel;

    public function init()
    {
        parent::init();
        $this->panel = [
            'type' => 'blue',
            'heading' => '<i class="fas fa-book"></i>  ' .  Yii::t('app', 'List'),
            'after' => false,
        ];

        if(!$this->pdfConfig) {
            $this->pdfConfig =  [
                'methods' => [
                    'SetTitle' => 'Sin titulo',
                    'SetSubject' => 'Subject',
                    'SetHeader' => ['Archivo generado el: ' . date("r")],
                    'SetFooter' => ['|Page {PAGENO}|'],
                    'SetAuthor' => 'Seisvalt',
                    'SetCreator' => 'Seisvalt',
                    'SetKeywords' => 'Yii2, Export, PDF, MPDF, Output, GridView, Grid, yii2-grid, yii2-mpdf, yii2-export',
                ]
            ];
        }
        if ($this->createButton) {
            $this->panel['before'] = $this->createButton;
        }
    }

    public function run()
    {
        $customDropdown = [
            'options' => ['tag' => false],
            'linkOptions' => ['class' => 'dropdown-item']
        ];

        $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $this->dataProvider,
            'columns' => $this->gridColumns,
            'target' => ExportMenu::TARGET_BLANK,
            'asDropdown' => false,
            'pjaxContainerId' => 'kv-pjax-container',
            'exportConfig' => [
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_EXCEL => false,
                ExportMenu::FORMAT_CSV => $customDropdown,
                ExportMenu::FORMAT_EXCEL_X => $customDropdown,
                ExportMenu::FORMAT_PDF => [
                    'options' => ['tag' => false],
                    'linkOptions' => ['class' => 'dropdown-item'],
                    'pdfConfig' => $this->pdfConfig
                ],
            ],
            'exportContainer' => [
            ],
            'clearBuffers' => true,
            'dropdownOptions' => [
                'label' => '<i class="fas fa-external-link-alt"></i>Exportar Todo',
            ],
        ]);

        return KGrid::widget([
            'dataProvider' => $this->dataProvider,
            'filterModel' => $this->searchModel,
            'columns' => $this->gridColumns,
            'toolbar' => [
                [
                    'options' => ['class' => 'btn-group-sm mr-2'],
                ],
                '{export}',
                '{toggleData}',
            ],
            'exportContainer' => [
                'class' => 'btn-group-sm mr-2 me-2'
            ],
            'exportConfig' => [
                'csv' => [],
                'xls' => [],
                'pdf' => [],
            ],
            'export' => [
                'itemsAfter'=> [
                    '<div role="presentation" class="dropdown-divider"></div>',
                    '<div class="dropdown-header">Exportar Todo</div>',
                    $fullExportMenu
                ]
            ],
            'responsive' => true,
            'panel' => $this->panel,
        ]);
    }
}
