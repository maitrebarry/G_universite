<?php



class App {
    protected $controller = 'Logins';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $URL = $this->getURL();

        if (isset($URL[0]) && file_exists('app/controller/' . ucfirst($URL[0]) . '.php')) {
            $this->controller = ucfirst($URL[0]);
            unset($URL[0]);
        }

        require_once 'app/controller/' . $this->controller . '.php';
        $this->controller = new $this->controller();

        if (isset($URL[1]) && method_exists($this->controller, $URL[1])) {
            $this->method = $URL[1]; // ⚠️ garder minuscule
            unset($URL[1]);
        }

        $this->params = $URL ? array_values($URL) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function getURL() {
        $url = $_GET['url'] ?? 'Logins';
        $url = filter_var(trim($url, '/'), FILTER_SANITIZE_URL);
        return explode('/', $url);
    }
}
