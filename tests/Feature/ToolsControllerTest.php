<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use App\Http\Controllers\Api\ToolsController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

use App\Models\Tool;

class ToolsControllerTest extends TestCase
{

    use WithoutMiddleware;

    /**
     * Testa se implementa Controller
     */
    public function test_if_should_extends_abstract_controller()
    {
        $controller = new ToolsController();

        $actual = $controller;
        $expected = Controller::class;
        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Testa se esta retornando a listagem de tools.
     */
    public function test_if_can_list_tools()
    {
        $uri = route('api.tools.index');
        $this->json('GET', $uri)
            ->assertStatus(200)
            ->assertSee('[')
            ->assertSee(']');
    }

    /**
     * Testa se é possivel criar um registro.
     *
     * @return void
     */
    public function test_if_can_create_an_tool_record()
    {
        $uri = route('api.tools.store');
        $bad_request = 400;
        $data = [
            "title" => "Titulo da Tool",
            "link" => "https://link.com",
            "description" => "description tool",
        ];

        $this->json('POST', $uri, $data)
            ->assertStatus(201)
            ->assertSee('id');

        $data = [];

        $this->json('POST', $uri, $data)
            ->assertSee('code')
            ->assertSee($bad_request)
            ->assertSee('message')
            ->assertSee('description')
            ->assertStatus($bad_request);


        $data = ["title" => null, 'link' => 'http://google.com'];
        $this->json('POST', $uri, $data)
            ->assertSee(json_encode(Lang::get('tools.validator.title.required')))
            ->assertStatus($bad_request);


        $data = ["title" => "a", 'link' => 'http://google.com'];
        $this->json('POST', $uri, $data)
            ->assertSee(json_encode(Lang::get('tools.validator.title.min')))
            ->assertStatus($bad_request);


        $data = ["title" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book It has survive.", 'link' => 'http://google.com'];
        $this->json('POST', $uri, $data)
            ->assertSee(json_encode(Lang::get('tools.validator.title.max')))
            ->assertStatus($bad_request);


        $data = ["title" => "Titulo", "link" => null];
        $this->json('POST', $uri, $data)
            ->assertSee(json_encode(Lang::get('tools.validator.link.required')))
            ->assertStatus($bad_request);


        $data = ["title" => "Titulo", "link" => "http"];
        $this->json('POST', $uri, $data)
            ->assertSee(json_encode(Lang::get('tools.validator.link.url')))
            ->assertStatus($bad_request);


        $data = ["title" => "Titulo", 'link' => 'http://google.com', "tags" => -1];
        $this->json('POST', $uri, $data)
            ->assertSee(json_encode(Lang::get('tools.validator.tags.array')))
            ->assertStatus($bad_request);


        $data = ["title" => "Titulo", 'link' => 'http://google.com', "tags" => "teste teste"];
        $this->json('POST', $uri, $data)
            ->assertSee(json_encode(Lang::get('tools.validator.tags.array')))
            ->assertStatus($bad_request);
    }

    /**
     * Testa se é possivel atualizar um registro
     */
    public function test_if_can_update_a_tool_record()
    {

        $data = [
            "title" => "Titulo",
            "link" => "https://link.com",
            "description" => "description",
        ];

        $tool = Tool::create($data);

        $update = [
            "title" => "Titulo updated",
            "link" => "https://link.com.updated",
            "description" => "description updated",
        ];

        $uri = route('api.tools.update', ['tool' => $tool]);
        $this->json('PUT', $uri, $update)
            ->assertStatus(200)
            ->assertSee("Titulo updated")
            ->assertSee("id");
    }

    /**
     * Testa se é possivel deletar um registro.
     */
    public function test_if_can_delete_a_tool_record()
    {
        $data = [
            "title" => "Titulo",
            "link" => "https://link.com",
            "description" => "description",
        ];

        $tool = Tool::create($data);

        $uri = route('api.tools.destroy', ['tool' => $tool]);

        $this->json('DELETE', $uri)
            ->assertStatus(204);
    }

    /**
     * Testa se ocorre erro ao deletar um registro que nao existe.
     */
    public function test_if_delete_not_found_a_tool()
    {
        $tool = new Tool();
        $tool->id = -1;

        $uri = route('api.tools.destroy', ['tool' => $tool]);
        $this->json('DELETE', $uri)
            ->assertStatus(404);
    }
}
