<?php

namespace ToDoPrviBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ToDoPrviBundle\Entity\Item;

class AdditionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('todo:add')
            ->setDescription('Add a new post to database')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Your todo item content'
            )
            ->addArgument(
                'location',
                InputArgument::REQUIRED,
                'Location of your todo item'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $item = new Item();

        $name = $input->getArgument('name');
        $location = $input->getArgument('location');

        $item->setName($name);
        $item->setLocation($location);
        $item->setDueDate(new \DateTime('tomorrow'));
        $item->setCreationTime(new \DateTime("now"));

        // write it into db
        $em=$this->getContainer()->get('doctrine.orm.entity_manager');
        $em->persist($item);
        $em->flush();

        $output->writeln("Successfuly added item to database.");
    }
}