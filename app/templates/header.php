<div class="navbar-wrapper">
    <nav class="navbar navbar-inverse navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          </button>
          <a class="navbar-brand" href="#">SQLI</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="<?=SCRIPT_ROOT?>/index.php">Home</a></li>
            <li><a href="<?=SCRIPT_ROOT?>/about.php">About</a></li>
            <li><a href="<?=SCRIPT_ROOT?>/contacts.php">Contact</a></li>
          </ul>
      <form class="navbar-form navbar-right col-md-pull-2" role="search">
        <div class="form-group">
          <form action='search' method='get'>
            <input type="text" class="form-control" name='query' placeholder="Search Threads">
          </form>
        </div>
      </form>
        </div>
      </div>
    </nav>
</div>