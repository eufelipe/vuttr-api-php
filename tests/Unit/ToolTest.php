<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Tool;

class ToolTest extends TestCase
{

    use DatabaseTransactions;
    use DatabaseMigrations;

    /**
     * Testa se é possível criar um novo registro.
     *
     */
    public function test_if_can_create_an_Tool()
    {
        $data = [
            "title" => "Notion",
            "link" => "https://notion.so",
            "description" => "description tool",
        ];

        $tool = Tool::create($data);

        $actual = Tool::all();
        $expected = 1;
        $this->assertCount($expected, $actual);

        $actual = $tool->id;
        $expected = 1;
        $this->assertEquals($expected, $actual);

        $actual = $tool->title;
        $expected = "Notion";
        $this->assertEquals($expected, $actual);

        $actual = $tool->link;
        $expected = "https://notion.so";
        $this->assertEquals($expected, $actual);

        $actual = $tool->description;
        $expected = "description tool";
        $this->assertEquals($expected, $actual);
    }


    /**
     * Testar se é possível atualizar um registro.
     * 
     */

    public function test_if_can_update_an_tool()
    {

        $data = [
            "title" => "Titulo",
            "link" => "https://link.com",
            "description" => "description",
        ];

        $tool = Tool::create($data);

        $actual = Tool::all();
        $expected = 1;
        $this->assertCount($expected, $actual);

        $data = [
            "title" => "Notion Updated",
            "link" => "https://link.com.updated",
            "description" => "description updated",
        ];

        Tool::whereId($tool->id)->update($data);

        $updated = Tool::find(1);

        $actual = $updated->title;
        $expected = "Notion Updated";
        $this->assertEquals($expected, $actual);

        $actual = $updated->link;
        $expected = "https://link.com.updated";
        $this->assertEquals($expected, $actual);
    }



    /**
     * Teste se é possivel deletar um registro.
     *
     */

    public function test_if_can_delete_an_tool_with_soft_delete()
    {

        $data = [
            "title" => "Titulo",
            "link" => "https://link.com",
            "description" => "description",
        ];
        
        $tool = Tool::create($data);

        $actual = Tool::all();
        $expected = 1;
        $this->assertCount($expected, $actual);

        $tool->delete();

        $actual = Tool::all();
        $expected = 0;
        $this->assertCount($expected, $actual);

        $actual = $tool->trashed();
        $expected = true;
        $this->assertEquals($expected, $actual);

        $actual = Tool::withTrashed()->get();
        $expected = 1;
        $this->assertCount($expected, $actual);

        $tool->forceDelete();

        $actual = Tool::withTrashed()->get();
        $expected = 0;
        $this->assertCount($expected, $actual);

    }
}
