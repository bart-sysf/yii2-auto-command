<?php

namespace blurrywindows\AutoCommand;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\Console;

class AutoCommand extends Controller
{
    const YES_VALUES = ['y', 'Y', 'yes', 'Yes'];
    const ACTIONS = ['gii-models', 'apidoc'];

    public $baseClass = 'yii\db\ActiveRecord';
    public $modelsFolder = 'models';
    public $modelsNamespace = 'app\models';
    public $apidocInputFolder = 'controllers';
    public $apidocOutputFolder = 'web/apidoc';
    public $skipTables = ['migration'];

    private $continue = false;
    private $npmBin = '';

    public function init()
    {
        parent::init();

        exec('npm bin', $this->npmBin);
        if (!$this->npmBin)
            throw new Exception('Please check if Node and it\'s packages are installed correctly.');
    }

    public function beforeAction($action)
    {
        if (YII_ENV_PROD && !$this->continue) {
            $continue = Console::prompt('This action should not be run in production. Continue? (y/n)', ['default' => 'n']);
            if (!in_array($continue, $this::YES_VALUES))
                return false;

            $this->continue = true;
        }

        return parent::beforeAction($action);
    }

    public function actionAll()
    {
        foreach ($this::ACTIONS as $action) {
            $this->runAction($action);
        }
    }

    public function actionGiiModels()
    {
        $tableNames = Yii::$app->db->schema->tableNames;

        foreach ($tableNames as $tableName) {
            if (!in_array($tableName, $this->skipTables)) {
                $mainClass = str_replace(' ', '', ucwords(str_replace('_', ' ', $tableName)));
                $modelClass = 'Base' . $mainClass;
                $this->run('gii/model', ['baseClass' => $this->baseClass, 'modelClass' => $modelClass, 'tableName' => $tableName, 'overwrite' => true, 'interactive' => false]);

                $mainFile = __DIR__ . '/../' . $this->modelsFolder . '/' . $mainClass . '.php';
                if (!file_exists($mainFile)) {
                    $body = <<<PHP
<?php

namespace {$this->modelsNamespace};

class {$mainClass} extends {$modelClass} 
{

}

PHP;
                    $handle = fopen($mainFile, 'w');
                    fwrite($handle, $body);
                    fclose($handle);
                }
            }
        }
    }

    public function actionApidoc()
    {
        exec('"' . $this->npmBin[0] . '/apidoc" -i ./' . $this->apidocInputFolder . ' -o ./' . $this->apidocOutputFolder); //todo aangepast, testen op windows
    }
}