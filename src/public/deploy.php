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
        'echo $PWD',
        'whoami',
        'compass -v',
        'sass -v',
        'git pull --ff',
        'git status',
        'git submodule sync',
        'git submodule update',
        'git submodule status',
        '(cd ' . __DIR__ . '/../module/Ui/assets/;pm2 stop server.js)',
        '(cd ' . __DIR__ . '/../module/Ui/assets/;npm cache clean)',
        '(cd ' . __DIR__ . '/../module/Ui/assets/;npm install)',
        '(cd ' . __DIR__ . '/../module/Ui/assets/;npm update)',
        '(cd ' . __DIR__ . '/../module/Ui/assets/;pm2 start node_modules/athene2-editor/server/server.js)',
        '(cd ' . __DIR__ . '/../module/Ui/assets/;bower cache clean)',
        '(cd ' . __DIR__ . '/../module/Ui/assets/;bower install)',
        '(cd ' . __DIR__ . '/../module/Ui/assets/;bower update)',
        '(cd ' . __DIR__ . '/../;sh hyperdrive.sh)',
        '(cd ' . __DIR__ . '/../module/Ui/assets/;grunt build)',
        '(cd ' . __DIR__ . '/../../;php-cli composer.phar self-update)',
        '(cd ' . __DIR__ . '/../../;php-cli composer.phar update)',
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