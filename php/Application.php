<?php

/**
 * Class handling the API
 */
class Application
{
    // Routes handled by php, these will not be available to angular.
    private static $routes = [
        '/api'         => 'api',
        '/auth/login'  => 'login',
        '/auth/logout' => 'logout',
    ];

    /**
     * Calls function associated with route, returns base html otherwise
     */
    public static function route($uri)
    {
        $trimmed = substr($uri, strlen(DOC_ROOT));

        if (array_key_exists($trimmed, self::$routes)) {
            self::{self::$routes[$trimmed]}();
        } else {
            include BASE_HTML;
        }
    }

    private static function api()
    {
        echo $_GET['test'] . ', ' . $_GET['other'];
    }

    /**
     * Logs user in
     */
    private static function login()
    {
        if (empty($_POST['username']) || empty($_POST['password'])) {
            header('Location: ' . DOC_ROOT . '/login');
        } else {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $user     = DB::query("SELECT * FROM users WHERE username = ?", 's', [$username])->fetch_object();
            User::login($user, $username, $password);
            header('Location: ' . DOC_ROOT);
        }
    }

    /**
     * Logs user out
     */
    private static function logout()
    {
        User::logout();
        header('Location: ' . DOC_ROOT);
        exit();
    }
}
