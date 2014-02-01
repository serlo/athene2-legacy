<?php set_time_limit(0); ?>
<?php putenv(__DIR__.'../../'); ?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>GIT DEPLOYMENT SCRIPT</title>
</head>
<body style="background-color: #000000; color: #FFFFFF; font-weight: bold; padding: 0 10px;">
<pre>
 .  ____  .    ____________________________
 |/      \|   |                            |
[| <span style="color: #FF0000;">&hearts; &hearts;</span> |]  | Git Deployment Script v0.1 |
 |___==___|  / &copy; oodavid 2012 |
              |____________________________|
    <?php
    /**
     * GIT DEPLOYMENT SCRIPT
     *
     * Used for automatically deploying websites via github or bitbucket, more deets here:
     *
     *        https://gist.github.com/1809044
     */

    // The commands
    $commands = array(
        "echo $PWD 2>&1",
        "whoami 2>&1",
        "git pull --ff 2>&1",
        "git status 2>&1",
        "git submodule sync 2>&1",
        "git submodule update 2>&1",
        "git submodule status 2>&1",
        "sh " . __DIR__ . "/../hyperdrive.sh 2>&1",
        "cd " . __DIR__ . "/../module/Ui/assets/;npm cache clean 2>&1",
        "cd " . __DIR__ . "/../module/Ui/assets/;npm install 2>&1",
        "cd " . __DIR__ . "/../module/Ui/assets/;npm update 2>&1",
        "pm2 dump && pm2 kill 2>&1",
        "pm2 start \"".__DIR__."/../module/Ui/assets/node_modules/athene2-editor/server/server.js\" 2>&1",
        "cd " . __DIR__ . "/../module/Ui/assets/;bower cache clean 2>&1",
        "cd " . __DIR__ . "/../module/Ui/assets/;bower install 2>&1",
        "cd " . __DIR__ . "/../module/Ui/assets/;bower update 2>&1",
        "cd " . __DIR__ . "/../module/Ui/assets/;grunt build 2>&1",
        "cd " . __DIR__ . "/../../;php-cli composer.phar update 2>&1",
    );

    // Run the commands for output
    $output = '';
    foreach ($commands AS $command) {
        // Run it
        $tmp = shell_exec($command);

        // Fallback on error
        if($tmp === null){
            $tmp = exec($command);
        }

        // Output
        $output = "<span style=\"color: #6BE234;\">\$</span> <span style=\"color: #729FCF;\">{$command}\n</span>";
        $output .= htmlentities(trim($tmp)) . "\n";
        echo $output;
        flush();
        ob_flush();
    }

    // Make it pretty for manual user access (and why not?)
    ?>
</pre>
</body>
</html>