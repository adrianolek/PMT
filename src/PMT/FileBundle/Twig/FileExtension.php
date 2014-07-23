<?php
namespace PMT\FileBundle\Twig;

use PMT\FileBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
class FileExtension extends \Twig_Extension
{
    private static $types = array('aac', 'aiff', 'ai', 'asp', 'avi', 'bmp', 'c', 'cpp', 'css', 'dat', 'dmg', 'doc', 'docx', 'dot', 'dotx', 'dwg', 'dxf', 'eps', 'exe', 'flv', 'gif', 'h', 'html', 'ics', 'iso', 'java', 'jpg', 'key', 'm4v', 'mid', 'mov', 'mp3', 'mp4', 'mpg', 'odp', 'ods', 'odt', 'otp', 'ots', 'ott', 'pdf', 'php', 'png', 'pps', 'ppt', 'psd', 'py', 'qt', 'rar', 'rb', 'rtf', 'sql', 'tga', 'tgz', 'tiff', 'txt', 'wav', 'xls', 'xlsx', 'xml', 'yml', 'zip');

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('thumb_path', array($this, 'getThumbPath')),
        );
    }

    public function getThumbPath(File $file)
    {
        if ($file->isImage()) {
            return $this->router->generate('thumb', array('key' => $file->getDownloadKey()));
        } else {
            if (in_array($file->getExtension(), self::$types)) {
                return '/images/types/'.$file->getExtension().'.png';
            } else {
                return '/images/types/other.png';
            }
        }
    }

    public function getName()
    {
        return 'file';
    }
}
