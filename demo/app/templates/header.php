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
            <li class="active"><a href="<?=SCRIPT_ROOT?>/index">Home</a></li>
            <li><a href="<?=SCRIPT_ROOT?>/about">About</a></li>
            <li><a href="<?=SCRIPT_ROOT?>/contacts">Contact</a></li>
          </ul>
      <form class="navbar-form navbar-right col-md-pull-2" action="search" role="search">
        <div class="form-group">
            <input type="text" class="form-control" name='query' placeholder="Search Threads">
            <button class='hidden' ></button>
        </div>
      </form>
        </div>
      </div>
    </nav>
</div>