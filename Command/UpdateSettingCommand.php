<?php

namespace BFOS\SettingsManagementBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use BFOS\SettingsManagementBundle\Command\CreateSettingCommand;

class UpdateSettingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bfos:setting:update')
            ->setDescription('Update a setting.')
            ->setDefinition(array(
                new InputArgument('name', InputArgument::REQUIRED, 'The setting name'),
                new InputOption('type', null,InputOption::VALUE_OPTIONAL, 'The setting type'),
                new InputOption('value', null, InputOption::VALUE_OPTIONAL, 'Set the setting value'),
                new InputOption('roles', null, InputOption::VALUE_OPTIONAL, 'Set the setting roles'),
            ))
            ->setHelp(<<<EOT
The <info>bfos:setting:update</info> command updates a setting:

  <info>php app/console bfos:setting:update name_setting</info>

You can optionally specify the type, the value and the roles as arguments, i.e.:

  <info>php app/console fos:user:update name_setting --type=type_setting --value="setting`s value" --roles=ROLE_ADMIN,ROLE_USER</info>

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
        $type   = $input->getOption('type');
        $value = (array) $input->getOption('value');
        $roles = $input->getOption('roles');
        if($roles) {
            $roles  = explode(',',$input->getOption('roles'));
            if(!is_array($roles)) {
                $roles[] = $roles;
            }
        }


        if (($roles || $type || $value) && $name) {
            $update = $msetting->updateSetting($name, $type, $value, $roles);
            if($update){
                $output->writeln(sprintf('The setting "%s" was updated successfully.', $name));
            } else {
                $output->writeln(sprintf('Unable to update setting "%s".', $name));
            }
        } else {
            $output->writeln('Nothing was changed. No argument was passed.');
        }
    }

    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
    }
}
