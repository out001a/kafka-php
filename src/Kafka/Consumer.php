<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// +---------------------------------------------------------------------------
// | SWAN [ $_SWANBR_SLOGAN_$ ]
// +---------------------------------------------------------------------------
// | Copyright $_SWANBR_COPYRIGHT_$
// +---------------------------------------------------------------------------
// | Version  $_SWANBR_VERSION_$
// +---------------------------------------------------------------------------
// | Licensed ( $_SWANBR_LICENSED_URL_$ )
// +---------------------------------------------------------------------------
// | $_SWANBR_WEB_DOMAIN_$
// +---------------------------------------------------------------------------

namespace Kafka;

/**
+------------------------------------------------------------------------------
* Kafka protocol since Kafka v0.8
+------------------------------------------------------------------------------
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$
+------------------------------------------------------------------------------
*/

class Consumer
{
    use \Psr\Log\LoggerAwareTrait;
    use \Kafka\LoggerTrait;

    // {{{ consts
    // }}}
    // {{{ members
    
    private $isRunning = false;

    protected $processes = array();

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @access public
     * @param $hostList
     * @param null $timeout
     */
    public function __construct()
    {
    }

    // }}}
    // {{{ public function start()

    /**
     * start consumer
     *
     * @access public
     * @return void
     */
    public function start(\Closure $consumer = null, $isBlock = true)
    {
        if ($this->isRunning) {
            $this->error('Has start consumer');
            return;
        }
        $this->setProcess($consumer, $isBlock);
        $this->isRunning = true;
        \Amp\run();
        $this->isRunning = false;
    }

    // }}}
    // }}}

    protected function setProcess(\Closure $consumer = null, $isBlock = true)
    {
        if (!isset($this->processes[$isBlock])) {
            $this->processes[$isBlock] = new \Kafka\Consumer\Process($consumer);
            if ($this->logger) {
                $this->processes[$isBlock]->setLogger($this->logger);
            }
            $this->processes[$isBlock]->start($isBlock);
        }
    }

}
