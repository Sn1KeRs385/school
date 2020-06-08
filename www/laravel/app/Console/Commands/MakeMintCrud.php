<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;

class MakeMintCrud extends Command
{

    protected $signature = 'make:mintcrud {name}';

    protected $description = 'Make mint migration, model, controller, policy ';

    protected $name;
    protected $names;

    protected $snake_case_name;
    protected $snake_case_names;

    protected $StudlyCaseName;
    protected $StudlyCaseNames;

    protected $camelCaseName;
    protected $camelCaseNames;
    
    private function makeAdminController()
    {
        $path = app_path("Http/Controllers/AdminControllers/{$this->StudlyCaseName}Controller.php");

        print($path . "\r\n");

        file_put_contents($path, "<?php

namespace App\Http\Controllers\AdminControllers;

use App\AdminModels\\{$this->StudlyCaseName};
use App\Http\Controllers\AdminControllers\CRUDController;

class {$this->StudlyCaseName}Controller extends CRUDController
{
    public \$modelClass = {$this->StudlyCaseName}::class;
}
");
    }

    private function makeAdminModel()
    {
        $path = app_path("AdminModels/{$this->StudlyCaseName}.php");

        print($path . "\r\n");

        file_put_contents($path, "<?php

namespace App\AdminModels;

use App\AdminModels\CRUDModel;
use Laravel\Scout\Searchable;

class {$this->StudlyCaseName} extends CRUDModel
{
    use Searchable;

    // php artisan scout:import \"App\AdminModels\\{$this->StudlyCaseName}\"
    // public function toSearchableArray()
    // {
    //     return \$this->toArray();
    // }

    protected \$casts = [

    ];

    protected \$fillable = [

    ];

    protected \$hidden = [
        'created_at',
        'updated_at',
    ];

}
");
    }

    private function makeAdminPanelTable()
    {

        $path = resource_path("assets/js/models/{$this->StudlyCaseName}.js");
        print($path . "\r\n");

        file_put_contents($path, "import ORM from '../api/ORM';

let model_name = '{$this->snake_case_names}';

export default {
    name: \"{$this->StudlyCaseName}\",
    url: \"{$this->snake_case_names}\",
    icon: \"fa fa-language fa-lg\",

    show: {
        add: true,
        edit: true,
        delete: true
    },

    table: {
        
        sort: {

        },

        fields: [{
                key: \"id\",
                label: \"#\"
            },
            {
                key: \"name\",
                label: \"Название\",
            }
        ],
    },
    form: {
        fields: [{
            label: 'Название',
            type: 'text',
            placeholder: 'Название',
            key: 'name',
            value: '',
        }]
    },
    methods: {
        async all() {
            return await ORM.all(model_name);
        },
        async paginate(page, per_page, q) {
            return await ORM.paginate(model_name, page, per_page, q);
        },
        async find(id) {
            return await ORM.find(model_name, id);
        },
        async create(data) {
            return await ORM.create(model_name, data);
        },
        async update(id, data) {
            return await ORM.update(model_name, id, data);
        },
        async delete(id) {
            return await ORM.delete(model_name, id);
        }
    }
}");
        $pathOldModelsIndexJs = resource_path("assets/js/models/index.js");

        $oldModelsIndexJs = file_get_contents($pathOldModelsIndexJs);

        $rows = explode("\n", $oldModelsIndexJs);

        $import = "import {$this->StudlyCaseName} from './{$this->StudlyCaseName}';";
        $export = "\t{$this->StudlyCaseName},";

        if (!in_array($import, $rows)) {
            array_splice($rows, 0, 0, $import);
        }

        if (!in_array($export, $rows)) {
            array_splice($rows, -1, 0, $export);
        }

        $newModelsIndexJs = implode("\n", $rows);

        file_put_contents($pathOldModelsIndexJs, $newModelsIndexJs);

        $pathTemplateIndex = resource_path("assets/js/views/pages/{$this->snake_case_names}/Index.vue");
        $pathTemplateForm = resource_path("assets/js/views/pages/{$this->snake_case_names}/Form.vue");

        $directoryPath = resource_path("assets/js/views/pages/{$this->snake_case_names}");

        if (!is_dir($directoryPath)) {
            mkdir($directoryPath);
        }

        file_put_contents($pathTemplateIndex, "<template>
<div>
    <Index :model=\"model\" />
</div>
</template>

<script>
import Index from \"../../../components/CRUD/Index\";
import model from \"../../../models/{$this->StudlyCaseName}\";
export default {
  name: \"{$this->StudlyCaseName}Index\",
  data() {
    return {
      model
    };
  },
  components: {
    Index
  }
};
</script>");

        file_put_contents($pathTemplateForm, "<template>
<div>
    <Form :model=\"model\" />
</div>
</template>

<script>
  import Form from \"../../../components/CRUD/Form\";
  import model from \"../../../models/{$this->StudlyCaseName}\";
  export default {
    name: \"{$this->StudlyCaseName}Form\",
    data() {
      return {
        model
      };
    },
    components: {
      Form
    }
  };
</script>");

    }

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->name = $this->argument('name');
        $this->names = str_plural($this->name);

        $this->snake_case_name = snake_case($this->name);
        $this->snake_case_names = snake_case($this->names);

        $this->StudlyCaseName = studly_case($this->name);
        $this->StudlyCaseNames = studly_case($this->names);

        $this->camelCaseName = camel_case($this->name);
        $this->camelCaseNames = camel_case($this->names);

        $this->makeAdminController();
        $this->makeAdminModel();

        Artisan::call("make:model", ['name' => "ApiModels/{$this->StudlyCaseName}"]);
        Artisan::call("make:controller", ['name' => "ApiControllers/{$this->StudlyCaseName}Controller"]);
        Artisan::call("make:migration", ['name' => "create_{$this->snake_case_names}_table", "--create" => $this->snake_case_names]);

        $this->makeAdminPanelTable();
    }
}
