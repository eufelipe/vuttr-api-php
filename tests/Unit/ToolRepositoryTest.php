<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\ToolRepository;
use App\Models\Tool;
use App\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Mockery;

class ToolRepositoryTest extends TestCase
{

    /**
     * Testa se a ToolRepository implementa um RepositoryInterface
     */

    public function test_if_tool_repository_implements_repository_interface()
    {
        $mock = Mockery::mock(ToolRepository::class);

        $actual = $mock;
        $expected = RepositoryInterface::class;
        $this->assertInstanceOf($expected, $actual);
    }


    /**
     * Testa se o método ->all() retornou corretamente
     */
    public function test_if_tool_repository_return_records()
    {

        $data = [
            "title" => "Notion",
            "link" => "https://notion.so",
            "description" => "description tool",
        ];

        $repository = resolve(ToolRepository::class);

        $repository->create($data);
        $repository->create($data);
        $repository->create($data);


        $actual = $repository->all();
        $expected = 3;
        $this->assertCount($expected, $actual);

        $actual = $repository->all()[0];
        $expected = Tool::class;
        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Testa se o método ->all( ['id', 'title'] ) retornou corretamente (com parametros)
     */

    public function test_if_tool_repository_return_records_with_only_argments_title_and_link()
    {
        $data = [
            "title" => "Meu titulo unico",
            "link" => "https://notion.so",
            "description" => "description tool",
        ];

        $repository = resolve(ToolRepository::class);
        $repository->create($data);

        $tool = $repository->all()[0];

        $actual = $tool->title;
        $expected = "Meu titulo unico";
        $this->assertEquals($expected, $actual);

        $tool = $repository->all('link')[0];

        $actual = $tool->title;
        $expected = null;
        $this->assertEquals($expected, $actual);

        $tool = $repository->all('title')[0];

        $actual = $tool->title;
        $expected = "Meu titulo unico";
        $this->assertEquals($expected, $actual);
    }


    /**
     *  Testa se é possivel criar uma nova tool
     */

    public function test_if_is_possible_create_an_new_tool_record()
    {

        $data = [
            "title" => "Meu titulo unico",
            "link" => "https://notion.so",
            "description" => "description tool",
        ];

        $repository = resolve(ToolRepository::class);
        $tool = $repository->create($data);
        $tool = $repository->create($data);

        $actual = $repository->all();
        $expected = 2;
        $this->assertCount($expected, $actual);

        $actual = $tool->title;
        $expected = "Meu titulo unico";
        $this->assertEquals($expected, $actual);
    }


    /**
     *  Testa se é possivel atualizar um tool
     */

    public function test_if_it_is_possible_to_update_an_tool_record()
    {

        $data = [
            "title" => "Meu titulo unico",
            "link" => "https://notion.so",
            "description" => "description tool",
        ];

        $repository = resolve(ToolRepository::class);
        $tool = $repository->create($data);

        $actual = $tool->title;
        $expected = "Meu titulo unico";
        $this->assertEquals($expected, $actual);

        $data = [
            "title" => "Meu titulo unico Updated",
            "link" => "https://link.com.updated",
            "description" => "description updated",
        ];

        $updated = $repository->update($data, $tool->id);

        $actual = $updated->title;
        $expected = "Meu titulo unico Updated";
        $this->assertEquals($expected, $actual);
    }

    /**
     * Testa se ocorre uma Exception ao tentar atualizar um registro que não existe.
     */

    public function test_if_it_is_possible_to_update_a_tool_record_and_a_failure_occurs()
    {
        $id = 0;
        $data = [
            "title" => "Meu titulo unico",
            "link" => "https://notion.so",
            "description" => "description tool",
        ];

        $repository = resolve(ToolRepository::class);

        $this->expectException(ModelNotFoundException::class);

        $repository->update($data, $id);
    }


    /**
     * Testa se é possivel deletar um registro
     */
    public function test_if_it_is_possible_to_delete_a_tool_record()
    {

        $data = [
            "title" => "Meu titulo unico",
            "link" => "https://notion.so",
            "description" => "description tool",
        ];

        $repository = resolve(ToolRepository::class);
        $tool = $repository->create($data);

        $result = $repository->delete($tool->id);

        $actual = $result;
        $expected = true;
        $this->assertEquals($expected, $actual);
    }

    /**
     * Testa se ocorre uma Exception ao tentar deletar um tool que não existe 
     */

    public function test_if_it_is_possible_to_delete_a_tool_and_a_failure_occurs()
    {

        $id = 1;

        $throw = new ModelNotFoundException();
        $throw->setModel(\stdClass::class);

        $repository = resolve(ToolRepository::class);

        $this->expectException(ModelNotFoundException::class);

        $repository->delete($id);
    }

    /**
     * Testa se é possivel buscar uma Tool
     */
    public function test_if_it_is_possible_to_find_a_tool()
    {

        $id = 1;
        $data = [
            "title" => "Meu titulo unico",
            "link" => "https://notion.so",
            "description" => "description tool",
        ];

        $repository = resolve(ToolRepository::class);
        $repository->create($data);

        $result = $repository->find($id);

        $actual = $result;
        $expected = Tool::class;
        $this->assertInstanceOf($expected, $actual);

        $actual = $result->title;
        $expected = "Meu titulo unico";
        $this->assertEquals($expected, $actual);
    }

    /**
     * Testa se é possivel buscar um Tool pela coluna title
     */
    public function test_if_it_is_possible_to_findBy_an_tool_record()
    {
        $data = [
            "title" => "Meu titulo unico",
            "link" => "https://notion.so",
            "description" => "description tool",
        ];

        $field = 'title';
        $value = "Meu titulo unico";

        $repository = resolve(ToolRepository::class);
        $repository->create($data);

        $result = $repository->findBy($field, $value);

        $actual = $result;
        $expected = 1;
        $this->assertCount($expected, $actual);

        $actual = $result[0];
        $expected = Tool::class;
        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Testa se é possivel buscar um Tool pela coluna title
     */
    public function test_if_it_is_possible_to_find_like_an_tool_record()
    {
        $data = [
            "title" => "Meu titulo unico",
            "link" => "https://notion.so",
            "description" => "description tool",
        ];

        $field = 'title';
        $value = "titulo";

        $repository = resolve(ToolRepository::class);
        $repository->create($data);

        $result = $repository->like($field, $value);

        $actual = $result;
        $expected = 1;
        $this->assertCount($expected, $actual);

        $actual = $result[0];
        $expected = Tool::class;
        $this->assertInstanceOf($expected, $actual);
    }
}
