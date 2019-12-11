<?php namespace template;

/**
 * The abstract HtmlPage provides the output buffering
 * and a cascading set of methods for rendering the page.
 * It can be extended to customize the HTML output.
 */
abstract class HtmlPage {
    private $started = false;
    protected $ob_contents = '';

    function __construct() {
        $this->user = new \StdClass();
        $this->title = '';
        $this->body_class = '';
    }
    function start() {
        if (!$this->started) {
            ob_start();
            $this->started = true;
        }
    }
    function render() {
        echo "<!DOCTYPE html><html lang='en-us'>";
        $this->renderHead();
        $this->renderBody();
        echo "</html>";
    }
     function renderHead() {
         echo "
             <head>
                 <meta charset=utf-8>
                 <title>{$this->title}</title>
                 {$this->renderStylesheets()}
                 {$this->renderJavascripts()}
             </head>
         ";
     }
    function renderStylesheets() {
        echo "<link rel='stylesheet' href='/css/welcome.css'/>";
    }
    function renderJavascripts() {
        echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>";
        echo "<script src='https://code.jquery.com/jquery-3.4.1.js' integrity='sha256-WpOohJOqMqqyKL9FccASBO0KwACQJpFTUBLTOVvVU=' crossorigin='anonymous'></script>";
        echo "<script type='text/javascript' src='/js/login_effect.js'></script>";
    }
    function renderBody() {
        echo "<body class='{$this->body_class}'>";
        $this->renderHeader();
        $this->renderMain();
        echo "</body>";
    }
    function renderHeader() {
        echo "<header class='header'>\n";
        echo "    <a href='/index.php'>Rock Climbing</a>\n";
        echo "    "; //TODO: IMAGE ICON THING
        echo "    <form>\n";
        echo "        <input id='myInput' placeholder='Search...' type='text' />\n";
        echo "        <input type='submit' value='Search' />\n";
        echo "    </form>\n";
        echo "</header>\n";
    }
    function renderNav($key) {
        echo "
    <div id='sidebar'>
      <div class='nav'>
        <strong>Site Navigation</strong>";
        if(!$this->user->email) {
          echo "
          <button class='active-open-button' onclick='openForm()'>Login</button>
          <div class='form-popup' id='myForm'>
            <form action='/forms/user-login.php' method='POST' class='form-container'>
            <label for='email'><b>Email</b></label>
            <input type='text' id='uname' placeholder='Enter Email' name='email' required>
            <label for='password'><b>Password</b></label>
            <input type='password' id='pwd' placeholder='Enter Password' name='password' required>
            <button type='submit' id='but-submit' onclick='relForm()' class='btn'>Login</button>
            </form>
          </div>
          <a href='/new-user.php'>Sign Up</a>";
        }
        if ($key == "POST") {
        echo "
          <a href='/'>Home</a>
          <a href='/category.php'>Forum Categories</a>";
        } else if(!$this->user->email) {
        echo "
          <a href='/'>Home</a>
          <a href='/category.php'>Forum Categories</a>";
        } else if($key == "EDIT_POST" or $key == "SHOW_POST") {
        echo "
          <a href='/'>Home</a>
          <a href='/category.php'>Forum Categories</a>";
        } else {
        echo "
          <a href='/' class='active'>Home</a>
          <a href='/category.php'>Forum Categories</a>";
        }
        if ($this->user->is_admin or $this->user->is_moderator) {
          echo "
          <a href='/admin/index.php'>Admin Control Panel</a>";
        }
        if ($this->user->email) {
          echo "
          <a href='/logout.php'>Logout</a>";
        }
        echo "
      </div>
    </div>";
    }
    function renderMain() {
        echo "<main>{$this->ob_contents}</main>";
    }
    function renderFooter() {
        echo "<footer class='footer'><a href='/index.php'>Welcome Page</a> | Date: " . date("Y-m-d") . "</footer>";
    }
    function __destruct() {
        if ($this->started) {
            $this->ob_contents = ob_get_clean();
            $this->render();
        }
    }
}

/**
 * This is pretty much the rendering for the Welcome Page.
 * 
 */
class StandardPage extends HtmlPage {
    function __construct() {
        parent::__construct();
        $this->title = 'My Discussion Board';
    }
}

class NewUserPage extends HtmlPage {
    function __construct() {
        parent::__construct();
        $this->title = 'Register an Account';
    }
    function renderNav() {
        echo "
    <div id='sidebar'>
      <div class='nav'>
        <strong>Site Navigation</strong>
        <ul>
          <button class='open-button' onclick='openForm()'>Login</button>
          <div class='form-popup' id='myForm'>
            <form action='/forms/user-login.php' method='POST' class='form-container'>
            <label for='email'><b>Email</b></label>
            <input type='text' placeholder='Enter Email' name='email' required>
            <label for='psw'><b>Password</b></label>
            <input type='password' placeholder='Enter Password' name='password' required>
            <button type='submit' onclick='relForm()' class='btn'>Login</button>
            </form>
          </div>
          <li>
            <a href='/new-user.php' class='active'>Sign Up</a>
          </li>
          <li>
            <a href='/'>Home</a>
          </li>
          <li>
            <a href='/category.php'>Forum Categories</a>
          </li>
        </ul>
      </div>
    </div>";
    }
}

class CategoryPage extends HtmlPage {
    
    function __construct() {
        parent::__construct();
        $this->title = 'Category Page';
    }
    function renderNav($category) {
        echo "
    <div id='sidebar'>
      <div class='nav'>
        <strong>Site Navigation</strong>
        <ul>";
        if (!$this->user->email):
          echo "
          <button class='open-button' onclick='openForm()'>Login</button>
          <div class='form-popup' id='myForm'>
            <form action='/forms/user-login.php' method='POST' class='form-container'>
            <label for='email'><b>Email</b></label>
            <input type='text' placeholder='Enter Email' name='email' required>
            <label for='psw'><b>Password</b></label>
            <input type='password' placeholder='Enter Password' name='password' required>
            <button type='submit' onclick='relForm()' class='btn'>Login</button>
            </form>
          </div>
          <li>
            <a href='/new-user.php'>Sign Up</a>
          </li>";
        endif;
        echo "
          <li>
            <a href='/'>Home</a>
          </li>
          <li>
            <a href='/category.php' class='active'>Forum Categories</a>
          </li>";
        if ($category->parent_category_id) {
          if ($this->user->email) {
          echo "
            <li>
              <a href='/new-post.php?slug=" . $category->slug . "'>Create a New Post</a>
            </li>";
          }
        }
        if ($this->user->is_admin or $this->user->is_moderator) {
          echo "
          <li>
            <a href='/admin/index.php'>Admin Control Panel</a>
          </li>";
          }
        if ($this->user->email) {
          echo "
          <li>
            <a href='/logout.php'>Logout</a>
          </li>";
        }
        echo "</ul>
      </div>
    </div>";
    }
}

/**
 * Removes the header and footer,
 * and centers the content in a panel.
 */
class MinimalPage extends HtmlPage {
    function renderHeader() {}
    function renderFooter() {}
    function renderStylesheets() {
        echo "<link rel='stylesheet' href='/css/minimal.css' />";
    }
}

/**
 * A fancier template for the admin pages
 */
class AdminPage extends HtmlPage {
    function __construct() {
        parent::__construct();
        $this->title = 'Admin Page';
    }
    function renderStylesheets() {
        echo "<link rel='stylesheet' href='/css/admin.css' />";
    }
    function renderJavascripts() {
        echo "<script type='text/javascript' src='/js/admin.js'></script>";
    }
    function renderHeader() {
        echo "<header>";
        echo "<strong>Welcome, {$this->user->name}</strong>";
        $this->renderNav();
        echo "</header>";
    }
    function renderNav() {
        echo "
            <nav>
                <a href='/index.php'>Home</a>
                <a href='/admin/create-user.php'>Create A User</a>
                <a href='/admin/list-users.php'>List/Manage Users</a>
                <a href='/logout.php'>Log out</a>
            </nav>
        ";
    }
}
