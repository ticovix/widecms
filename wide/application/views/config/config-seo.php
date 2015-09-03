<div class="header">
    <h1 class="page-title">Optimização de página (SEO)</h1>
</div>

<ul class="breadcrumb">
    <li><a href="<?php echo ROUTES::baseLink() ?>/home">Home</a></li>
    <li><a href="<?php echo ROUTES::baseLink() ?>/pages">Páginas</a></li>
    <li class="active">Optimização de página (SEO)</li>
</ul>
<div class="container-fluid">
    <div class="row-fluid">
        <form id="tab" method="post" enctype="multipart/form-data">                
            <div class="btn-toolbar">
                <input type="submit" class="btn btn-primary" name="send" value="Salvar">
            </div>
            <div class="well">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#home" data-toggle="tab">Optimização geral</a></li>
                    <li><a href="#googleplus" data-toggle="tab">Google+</a></li>
                    <li><a href="#twitter" data-toggle="tab">Twitter</a></li>
                    <li><a href="#open-graph" data-toggle="tab">Open Graph (Facebook e outros)</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane active in" id="home">
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="title_all" value="<?php echo $title_all ?>" class="form-control"> <sub>Digite no máximo 70 caracteres</sub>
                        </div>
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea type="text" name="description_all" class="form-control"><?php echo $description_all ?></textarea> <sub>Digite no máximo 230 caracteres</sub>
                        </div>
                        <div class="form-group">
                            <label>Impedir o google de traduzir esta página</label>
                            <select name="notranslate" class="form-control">
                                <option <?php if ($notranslate == '') { ?>selected="selected"<?php } ?> value="" selected="">Não</option>
                                <option <?php if ($notranslate == 'notranslate') { ?>selected="selected"<?php } ?> value="notranslate">Sim</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Palavras chaves</label>
                            <input type="text" name="keywords_all" value="<?php echo $keywords_all ?>" class="form-control">
                            <sup>Coloque palavras chaves separadas por vírgula (ex: site, loja virtual, desenvolvimento), utilize de preferência no máximo 10 palavras chaves.</sup>
                        </div>
                        <div class="form-group">
                            <label>Código Google Webmaster</label>
                            <input type="text" name="code_webmaster" value="<?php echo $code_webmaster ?>" class="form-control"> <sub>Coloque o código preferencialmente na página home para verificar seu site (<a href="https://www.google.com/webmasters/" target="_blank">Cadastre-se no Google Webmaster</a>)</sub>
                        </div>
                        <div class="form-group">
                            <label>Robot</label><br>
                            <label><input type="checkbox" <?php if (in_array('noindex', $robot)) { ?>checked="checked"<?php } ?> value="noindex" name="robot[]"> Impedir que a página seja indexada</label><br>
                            <label><input type="checkbox" <?php if (in_array('nofollow', $robot)) { ?>checked="checked"<?php } ?> value="nofollow" name="robot[]"> Evitar que o Googlebot siga os links a partir desta página<br></label><br>
                            <label><input type="checkbox" <?php if (in_array('nosnippet', $robot)) { ?>checked="checked"<?php } ?> value="nosnippet" name="robot[]"> Evitar que um snippet seja exibido nos resultados de pesquisa<br></label><br>
                            <label><input type="checkbox" <?php if (in_array('noodp', $robot)) { ?>checked="checked"<?php } ?> value="noodp" name="robot[]"> Evitar o uso da descrição alternativa do ODP/DMOZ<br></label><br>
                            <label><input type="checkbox" <?php if (in_array('noimageindex', $robot)) { ?>checked="checked"<?php } ?> value="noimageindex" name="robot[]"> Evitar que o Google exiba imagens da página no Google Image<br></label><br>
                        </div>
                    </div>
                    <div class="tab-pane" id="googleplus">
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="title_google" value="<?php echo $title_google ?>" class="form-control">  <sub>Digite no máximo 70 caracteres</sub>
                        </div>
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea type="text" name="description_google" class="form-control"><?php echo $description_google ?></textarea> <sub>Digite no máximo 230 caracteres</sub>
                        </div>
                        <div class="form-group">
                            <label>Autor</label>
                            <input type="text" name="author_google" value="<?php echo $author_google ?>" class="form-control"> <sub>Ex: https://plus.google.com/(Google+_Profile)/posts</sub>
                        </div>
                        <div class="form-group">
                            <label>Editor</label>
                            <input type="text" name="publisher_google" value="<?php echo $publisher_google ?>" class="form-control"> <sub>Ex: https://plus.google.com/(Google+_Page_Profile)</sub>
                        </div>
                        <div class="form-group">    
                            <label>Imagem</label>
                            <input type="file" name="image_google" value="<?php echo $image_google ?>" class="form-control"> <sub>Tamanho redimensionado para 300px de largura</sub>
                            <?php if (!empty($google_image)) { ?>
                                <br><br>
                                Imagem atual<br><br>
                                <img src="<?php echo $VERIFY_ACCESS->get_data_client()->address ?>/website/application/template/upload/<?php echo $google_image ?>" class="thumbnail" width="300">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="tab-pane" id="twitter">
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="title_twitter" value="<?php echo $title_twitter ?>" class="form-control">  <sub>Digite no máximo 70 caracteres</sub>
                        </div>
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea type="text" name="description_twitter" class="form-control"><?php echo $description_twitter ?> </textarea> <sub>Digite no máximo 200 caracteres</sub>
                        </div>
                        <div class="form-group">
                            <label>Autor</label>
                            <input type="text" name="creator_twitter" value="<?php echo $creator_twitter ?>" class="form-control"> <sub>Conta do Twitter do autor do texto (incluindo arroba)</sub>
                        </div>
                        <div class="form-group">
                            <label>Editor</label>
                            <input type="text" name="site_twitter" value="<?php echo $site_twitter ?>" class="form-control"> <sub>Conta do Twitter do site (incluindo arroba)</sub>
                        </div>
                        <div class="form-group">
                            <label>Imagem</label>
                            <input type="file" name="image_twitter" value="<?php echo $image_twitter ?>" class="form-control"> <sub>Tamanho redimensionado para 300px de largura</sub>
                            <?php if (!empty($twitter_image)) { ?>
                                <br><br>
                                Imagem atual<br><br>
                                <img src="<?php echo $VERIFY_ACCESS->get_data_client()->address ?>/website/application/template/upload/<?php echo $twitter_image ?>" class="thumbnail" width="300">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="tab-pane" id="open-graph">
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="title_open_graph" value="<?php echo $title_open_graph ?>" class="form-control">  <sub>Digite no máximo 70 caracteres</sub>
                        </div>
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea type="text" name="description_open_graph" class="form-control"><?php echo $description_open_graph ?></textarea> <sub>Digite no máximo 200 caracteres</sub>
                        </div>
                        <div class="form-group">
                            <label>Imagem</label>
                            <input type="file" name="image_open_graph" value="<?php echo $image_open_graph ?>" class="form-control"> <sub>Tamanho redimensionado para 300px de largura</sub>
                            <?php if (!empty($og_image)) { ?>
                                <br><br>
                                Imagem atual<br><br>
                                <img src="<?php echo $VERIFY_ACCESS->get_data_client()->address ?>/website/application/template/upload/<?php echo $og_image ?>" class="thumbnail" width="300">
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    var USER = function () {
        return "<?php echo ROUTES::getURL(1) ?>";
    }
    var URL = function () {
        return "<?php echo ROUTES::baseLink() ?>";
    }
</script>