
        <div class="header">

            <h1 class="page-title">FTP</h1>
        </div>
        
                <ul class="breadcrumb">
            <li><a href="<?php echo ROUTES::baseLink()?>/home">Home</a></li>
            <li class="active">FTP</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
                    

        <div class="block">
            <p class="block-heading">Imagens</p>
            <div class="box_file_upload">
                <form method="post" class="up_image" enctype="multipart/form-data">
                    <input type="hidden" name="hasimage" value="true">
                    <input type="file" name="upload_image[]" class="file_upload" data-type="up_image" multiple>
                </form>
            </div>
            <div class="block-body gallery">
                <input type="text" class="text_tab form-control" placeHolder="Clique na imagem e recupere o link aqui">
                <?php 
                if(count($img)>0){
                    foreach ($img as $value) {
                ?>
                <img src="<?php echo $data_client->address ?>/<?php echo $data_client->path_upload ?>/<?php echo $value["image"]?>" data-dir="<?php echo $data_client->address ?>/<?php echo $data_client->path_upload ?>/<?php echo $value["image"]?>" data-type="media" data-file="<?php echo $value["image"];?>" class="img-polaroid image_gallery">
                <?php 
                    }
                }else{
                ?>
                    Nenhuma imagem adicionada.
                <?php 
                }
                ?>
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Notas:</strong> 
                    <ul>
                        <li>Para copiar o link da imagem clique uma vez na mesma e copie o link acima;</li>
                        <li>Para deletar a imagem clique duas vezes em cima da mesma.</li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
            <!--div class="block">
            <p class="block-heading">Arquivos diversos</p>

            <div class="box_file_upload">
                <form method="post" class="up_file" enctype="multipart/form-data">
                    <input type="hidden" name="hasfile" value="true">
                    <input type="file" name="upload_files[]" class="file_upload" data-type="up_file" multiple>
                </form>
            </div>
            
            <div class="block-body gallery">
                <table class="table">
                    <tr>
                        <td><strong>Arquivo</strong></td>
                        <td><strong>Caminho</strong></td>
                        <td colspan="2"><strong>Data enviada</strong></td>
                    </tr>
                <?php 
                if(count($file)>0){
                    $x = 0;
                    foreach ($file as $value) {
                        $arr_dot = explode(".", $value["file"]);
                        $ext     = $arr_dot[count($arr_dot)-1];
                        if(image_extension($ext)){
                            $ext = $ext.".png";
                        }else{
                            $ext = "other.png";
                        }
                        $bg = ($x%2==0)?"#fff":"#eee";
                ?>
                <tr>
                    <td style="vertical-align:middle; background:<?php echo $bg?>;" ><img src="<?php echo ROUTES::baseUrl()?>/template/images/icons/<?php echo $ext?>" align="baseline" style="width:28px;"><?php echo $value["file"]?></td>
                    <td style="vertical-align:middle; background:<?php echo $bg?>;"><a href="<?php echo ROUTES::baseLink()?>/template/upload/<?php echo $value["file"]?>" target="_blank"><?php echo ROUTES::baseLink()?>/template/upload/<?php echo $value["file"]?></a></td>
                    <td style="vertical-align:middle; background:<?php echo $bg?>;"><?php echo datetime_format_db($value["datetime"]);?></td>
                    <td style="vertical-align:middle; background:<?php echo $bg?>;" width="25"><a href="<?php echo ROUTES::baseLink()?>/media/delfile/<?php echo $value["file"]?>"><img src="<?php echo ROUTES::baseUrl()?>/template/images/cross.png" style="width:25px;"></a></td>
                </tr>
                </td></tr>
                <?php 
                        $x++;
                    }
                }else{
                ?>
                    <tr><td colspan="3">Nenhuma imagem adicionada.</td></tr>
                <?php 
                }
                ?>
                </table>
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Nota:</strong> Para deletar o arquivo clique no "X" correspondente.</li>
                </div>
                <div class="clearfix"></div>
            </div>
        </div-->

    </div>
</div>
    
<script language="javascript" type="text/javascript">
$(function() {
    $(".gallery img").dblclick(function(){
        var $image = $(this).attr("data-file");
        var $type  = $(this).attr("data-type");
        if(confirm("Deseja realmente deletar essa imagem ?")){
            location.href="<?php echo ROUTES::baseLink()?>/media/del/"+$image+"/"+$type;
        }
    });
    $(".image_gallery").click(function(){
        var $src = $(this).attr("data-dir");
        $(".text_tab").val($src);
    });
    $(".text_tab").click(function(){
        $(this).select();
    });
    $(".file_upload").change(function(){
        var $type = $(this).attr("data-type");
        $("."+$type).submit();
    });
});
</script>