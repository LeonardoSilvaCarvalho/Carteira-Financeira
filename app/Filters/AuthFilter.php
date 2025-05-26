<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Metodo executado antes de o controlador ser chamado.
     *  Verifica se o usuario esta autenticado pela sessao.
     *
     * @param RequestInterface $request
     * @param array|null $arguments
     * @param  mixed|null
     */

    public function before(RequestInterface $request, $arguments = null)
    {
        // Verifica se existe um ID de usuario na sessão
        if (!session()->get('user_id')) {
            // se nao estiver logado redireciona para a pagina inicial com uma menssagem
            return redirect()->to('/')->with('msg', 'Você precisa estar logado.');
        }
    }

    /**
     * Metodo executado apos o controlador ser chamado e a resposta gerada.
     * neste caso, nao e ultilizado
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nenhuma ação pos-resposta foi definida
    }
}
