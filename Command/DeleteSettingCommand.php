<?php

namespace BFOS\SettingsManagementBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use BFOS\SettingsManagementBundle\Command\CreateSettingCommand;

class DeleteSettingCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('bfos:setting:delete')
            ->setDescription('Delete a setting.')
            ->setDefinition(array(
                new InputArgument('name', InputArgument::REQUIRED, 'The setting name'),
            ))
            ->setHelp(<<<EOT
The <info>bfos:setting:delete</info> command deletes a setting:

  <info>php app/console bfos:setting:delete name_setting</info>

EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var \BFOS\SettingsManagementBundle\Manager\ManagerSettings $msetting
         */
        $msetting = $this->getContainer()->get('bfos_setting_management.setting_manager');

        $name   = $input->getArgument('name');

        if($msetting->deleteSetting($name)){
            $output->writeln(sprintf('The setting "%s" was deleted successfully.', $name));
        } else {
            $output->writeln(sprintf('Unable to delete setting "%s".', $name));
        }
    }

    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if(!$input->getArgument('name')) {
            $name = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please insert a setting name: ',
                function($name)
                {
                    if (empty($name)) {
                        throw new \Exception('Name can not be empty');
                    }

                    return $name;
                }
            );
            $input->setArgument('name', $name);
        }
    }
}
