<?php


namespace Symfony\Component\Finder\Adapter;

@trigger_error('The ' . __NAMESPACE__ . '\BsdFindAdapter class is deprecated since version 2.8 and will be removed in 3.0. Use directly the Finder class instead.', E_USER_DEPRECATED);

use Symfony\Component\Finder\Expression\Expression;
use Symfony\Component\Finder\Iterator\SortableIterator;
use Symfony\Component\Finder\Shell\Command;
use Symfony\Component\Finder\Shell\Shell;


class BsdFindAdapter extends AbstractFindAdapter
{


    public function getName()
    {
        return 'bsd_find';
    }


    protected function canBeUsed()
    {
        return in_array($this->shell->getType(), array(Shell::TYPE_BSD, Shell::TYPE_DARWIN)) && parent::canBeUsed();
    }


    protected function buildFormatSorting(Command $command, $sort)
    {
        switch ($sort) {
            case SortableIterator::SORT_BY_NAME:
                $command->ins('sort')->add('| sort');

                return;
            case SortableIterator::SORT_BY_TYPE:
                $format = '%HT';
                break;
            case SortableIterator::SORT_BY_ACCESSED_TIME:
                $format = '%a';
                break;
            case SortableIterator::SORT_BY_CHANGED_TIME:
                $format = '%c';
                break;
            case SortableIterator::SORT_BY_MODIFIED_TIME:
                $format = '%m';
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unknown sort options: %s.', $sort));
        }

        $command
            ->add('-print0 | xargs -0 stat -f')
            ->arg($format . '%t%N')
            ->add('| sort | cut -f 2');
    }


    protected function buildFindCommand(Command $command, $dir)
    {
        parent::buildFindCommand($command, $dir)->addAtIndex('-E', 1);

        return $command;
    }


    protected function buildContentFiltering(Command $command, array $contains, $not = false)
    {
        foreach ($contains as $contain) {
            $expr = Expression::create($contain);


            $command
                ->add('| grep -v \'^$\'')
                ->add('| xargs -I{} grep -I')
                ->add($expr->isCaseSensitive() ? null : '-i')
                ->add($not ? '-L' : '-l')
                ->add('-Ee')->arg($expr->renderPattern())
                ->add('{}');
        }
    }
}
