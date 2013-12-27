<?php

/*
 * This file is part of PHPExifTool.
 *
 * (c) 2012 Romain Neutron <imprec@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPExiftool\Driver\Tag\IFD0;

use JMS\Serializer\Annotation\ExclusionPolicy;
use PHPExiftool\Driver\AbstractTag;

/**
 * @ExclusionPolicy("all")
 */
class MakerNoteSafety extends AbstractTag
{

    protected $Id = 50741;

    protected $Name = 'MakerNoteSafety';

    protected $FullName = 'Exif::Main';

    protected $GroupName = 'IFD0';

    protected $g0 = 'EXIF';

    protected $g1 = 'IFD0';

    protected $g2 = 'Image';

    protected $Type = 'int16u';

    protected $Writable = true;

    protected $Description = 'Maker Note Safety';

    protected $Values = array(
        0 => array(
            'Id' => 0,
            'Label' => 'Unsafe',
        ),
        1 => array(
            'Id' => 1,
            'Label' => 'Safe',
        ),
    );

}