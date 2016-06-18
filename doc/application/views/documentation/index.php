<!-- Page Content -->
<div class="container">
    <!-- Portfolio Section -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $topic['name']; ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <nav> 
                <ul class="nav bs-docs-sidenav nav-"> 
                    <?php
                    if ($subtopics) {
                        foreach ($subtopics as $subtopic) {
                            ?>
                            <li class="active"> 
                                <a href="<?php echo base_url($topic['slug'] . '/' . $subtopic['slug']) ?>"><?php echo $subtopic['title'] ?></a> 
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </nav>
        </div>
        <div class="col-md-9">
            <h2><?php echo $current_topic['title'] ?></h2>
            <p><?php echo $current_topic['text'] ?></p>
        </div>
    </div>
</div>