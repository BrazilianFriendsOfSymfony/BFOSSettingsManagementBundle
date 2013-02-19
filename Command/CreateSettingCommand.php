<?php

namespace BFOS\SettingsManagementBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class CreateSettingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bfos:setting:create')
            ->setDescription('Create a setting.')
            ->setDefinition(array(
                new InputArgument('name', InputArgument::REQUIRED, 'The setting name'),
                new InputArgument('type', InputArgument::REQUIRED, 'The setting type'),
                new InputOption('value', null, InputOption::VALUE_OPTIONAL, 'Set the setting value'),
                new InputOption('roles', null, InputOption::VALUE_OPTIONAL, 'Set the setting roles', 'ROLE_ADMIN'),
                new InputOption('label', null, InputOption::VALUE_OPTIONAL, 'Set the setting label text. Used as the setting name for the user.'),
                new InputOption('helpText', null, InputOption::VALUE_OPTIONAL, 'Set the setting help text.'),
        ))
            ->setHelp(<<<EOT
The <info>bfos:setting:create</info> command creates a setting:

  <info>php app/console bfos:setting:create image_size</info>

This interactive shell will ask you for an name and then a type [text, email_template].

You can alternatively specify the type as the second arguments:

  <info>php app/console fos:user:create image_size email_template</info>

If You can not specify a value as the third arguments, it is just it let blank.

The same thing can be do with the roles, fourth argument. And then this arguments can be specify at administration area.

Supported types are

text
email_template
email_address
boolean
integer
number
html
email_notification

EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var \BFOS\SettingsManagementBundle\Manager\SettingsManager $msetting
         */
        $msetting = $this->getContainer()->get('bfos_setting_management.setting_manager');

        $name   = $input->getArgument('name');
        $type   = $input->getArgument('type');
        $value = null;
        if($type == 'text' && !$input->getOption('value')) {
            $value['value'] = $input->getOption('value');
        }

        $roles  = explode(',',$input->getOption('roles'));

        $label = null;
        $help = null;
        if($input->getOption('label')){
            $label = $input->getOption('label');
        }
        if($input->getOption('helpText')){
            $help = $input->getOption('helpText');
        }

        if ($name && $type) {
            $msetting->createSetting($name, $type, $value, $roles, $label, $help);
            $output->writeln('The setting was created successfully.');
        }

    }


    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {

        if (!$input->getArgument('name')) {
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

        if (!$input->getArgument('type')) {
            $type = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please insert a setting type: ',
                function($type)
                {
                    if (empty($type)) {
                        throw new \Exception('Type can not be empty');
                    }
                    return $type;
                }
            );
            $input->setArgument('type', $type);
        }
    }
}
