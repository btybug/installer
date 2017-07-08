<?php
/**
 * Created by PhpStorm.
 * User: menq
 * Date: 7/8/17
 * Time: 10:06 PM
 */

namespace Avatar\Avatar\Http\Controllers;


use Illuminate\Routing\Controller;
use Symfony\Component\Process\Process;

class IndexConroller extends Controller
{
    public function getIndex()
    {
        return view('core_avatar::index');
    }

    public function composerUpdate()
    {
        $process = new Process('./composer.phar dump-autoload -o');
dd($process->start());
        define('EXTRACT_DIRECTORY', "../var/extractedComposer");


        if (file_exists(EXTRACT_DIRECTORY.'/vendor/autoload.php') == true) {
            echo "Extracted autoload already exists. Skipping phar extraction as presumably it's already extracted.";
        }
        else{
            $composerPhar = new \Phar("Composer.phar");
            //php.ini setting phar.readonly must be set to 0
            $composerPhar->extractTo(EXTRACT_DIRECTORY);
        }
//
////This requires the phar to have been extracted successfully.
//        require_once (EXTRACT_DIRECTORY.'/vendor/autoload.php');

////Use the Composer classes
//    use Composer\Console\Application;
//    use Composer\Command\UpdateCommand;
//    use Symfony\Component\Console\Input\ArrayInput;
//
//// change out of the webroot so that the vendors file is not created in
//// a place that will be visible to the intahwebz
//        chdir('../');
//
////Create the commands
//        $input = new ArrayInput(array('command' => 'update'));
//
////Create the application and run it with the commands
//        $application = new Application();
//        $application->run($input);

        return redirect()->back()->with(['message' =>'<pre>'. $echo.'</pre>']);
    }
}