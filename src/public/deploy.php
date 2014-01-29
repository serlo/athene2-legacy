<?php
set_time_limit(0);

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
    'git pull --ff',
    'git status',
    'git submodule sync',
    'git submodule update',
    'git submodule status',
    'sh ' . __DIR__ . '/../hyperdrive.sh',
    'cd ' . __DIR__ . '/../module/Ui/assets/ && pm2 dump',
    'cd ' . __DIR__ . '/../module/Ui/assets/ && npm clean',
    'cd ' . __DIR__ . '/../module/Ui/assets/ && npm install',
    'cd ' . __DIR__ . '/../module/Ui/assets/ && npm update',
    'cd ' . __DIR__ . '/../module/Ui/assets/ && bower clean',
    'cd ' . __DIR__ . '/../module/Ui/assets/ && bower install',
    'cd ' . __DIR__ . '/../module/Ui/assets/ && bower update',
    'cd ' . __DIR__ . '/../module/Ui/assets/ && grunt build',
    'cd ' . __DIR__ . '/../module/Ui/assets/ && pm2 start node_modules/athene2-editor/server/server.js',
    'cd ' . __DIR__ . '/../../ && php composer.phar self-update',
    'cd ' . __DIR__ . '/../../ && php composer.phar update',
);

// Run the commands for output
$output = '';
foreach ($commands AS $command) {
    // Run it
    $tmp = shell_exec($command);
    // Output
    $output .= "<span style=\"color: #6BE234;\">\$</span> <span style=\"color: #729FCF;\">{$command}\n</span>";
    $output .= htmlentities(trim($tmp)) . "\n";
}

// Make it pretty for manual user access (and why not?)
?>
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

    <?php echo $output; ?>
</pre>
</body>
</html>