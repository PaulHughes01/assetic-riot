<?php 
namespace PaulHughes01\Riot\Assetic;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\BaseNodeFilter;
use Assetic\Util\FilesystemUtils;
use Assetic\Exception\FilterException;

/**
 * Assetic filter to compile Riot tags.
 */
class RiotFilter extends BaseNodeFilter
{
    private $nodeBin;

    public function __construct($nodeBin = null)
    {
        $this->nodeBin = $nodeBin ? $nodeBin : '/usr/bin/node';
    }
    
    public function filterLoad(AssetInterface $asset) {}

    public function filterDump(AssetInterface $asset)
    {
        $compiler = __DIR__ . '/js/compile.js';
        
        $pb = $this->createProcessBuilder(
            array( $this->nodeBin, $compiler )
        );

        // input and output files
        $input  = FilesystemUtils::createTemporaryFile('riotcompiler_in');
        $output = FilesystemUtils::createTemporaryFile('riotcompiler_out');

        file_put_contents($input, $asset->getContent());
        
        // Build the process for the js/compile.js node app.
        $pb->add($input)->add('-o')->add($output);

        $proc = $pb->getProcess();
        $code = $proc->run();
        unlink($input);

        // If something went wrong...
        if (0 !== $code) {
            if (file_exists($output)) {
                unlink($output);
            }

            if (127 === $code) {
                throw new \RuntimeException('Path to node executable could not be resolved.');
            }

            throw FilterException::fromProcess($proc)->setInput($asset->getContent());
        }

        if (!file_exists($output)) {
            throw new \RuntimeException('Error creating output file.');
        }

        // Good to go!
        $asset->setContent(file_get_contents($output));

        unlink($output);
    }
}