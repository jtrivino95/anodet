<?php

class AppKernel
{
    public function boot()
    {
        $database = new \Anodet\Core\Database\Database([
            'host' => '127.0.0.1',
            'user' => 'tsp',
            'password' => 'tsp',
            'name' => 'anodet',
        ]);
    }
}
