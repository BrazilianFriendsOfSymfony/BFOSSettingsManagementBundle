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
                new InputOption('roles', null, InputOption::VALUE_OPTIONAL, 'Set the setting roles', 'ROLE_USER'),
            ))
            ->setHelp(<<<EOT
The <info>bfos:setting:create</info> command creates a setting:

  <info>php app/console bfos:setting:create image_size</info>

This interactive shell will ask you for an name and then a type [text, email_template].

You can alternatively specify the type as the second arguments:

  <info>php app/console fos:user:create image_size email_template</info>

If You can not specify a value as the third arguments, it is just it let blank.

The same thing can be do with the roles, fourth argument. And then this arguments can be specify at administration area.

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

        if ($name && $type) {
            $msetting->createSetting($name, $type, $value, $roles);
            $output->writeln('The setting was created successfully.');
        }

    }


    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var \BFOS\SettingsManagementBundle\Manager\SettingsManager $msetting
         */
        $msetting = $this->getContainer()->get('bfos_setting_management.setting_manager');

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
//        if($input->getArgument('type') == 'text') {
//            $value = $this->getHelper('dialog')->askAndValidate(
//                $output,
//                'Please insert a text value: ',
//                function($value)
//                {
//                    return $value;
//                }
//            );
//            $input->setArgument('value', $value);
//        }
//        $values = array();
//        if($input->getArgument('type') == 'email_template') {
//            $value = $this->getHelper('dialog')->askAndValidate(
//                $output,
//                'Please insert a text value: ',
//                function($value)
//                {
//                    $values['text'] = $value;
//                    return $value;
//                }
//            );
//            $input->setArgument('value', $value);
//
//            $value = $this->getHelper('dialog')->askAndValidate(
//                $output,
//                'Please insert a html value: ',
//                function($value)
//                {
//                    $value['html'] = $value;
//                    return $value;
//                }
//            );
//            $input->setArgument('value', $value);
//        }
//
//        $typeOptionsKeys = array_keys($msetting->getTypeOptions());
//        while (!in_array($input->getArgument('type'), $typeOptionsKeys) ) {
//            $type = $this->getHelper('dialog')->askAndValidate(
//                $output,
//                'Please insert a valid setting type [text, email_template]: ',
//                function($type) {
//
//                    if (empty($type)) {
//                        throw new \Exception('Type can not be empty');
//                    }
//                     return $type;
//                }
//            );
//
//            $input->setArgument('type', $type);
//        }
//
//        $rolesOptionKeys = array_keys($msetting->getRolesOptions());
//        $count_roles = count($rolesOptionKeys);
//
//        if (!$input->getArgument('roles')) {
//            $role = $this->getHelper('dialog')->askAndValidate(
//                $output,
//                'Granted editing for: ',
//                function($role)
//                {
//                    if (!empty($role)) {
//                        $roles[] = $role;
//                        return $roles;
//                    }
//                }
//            );
//            $input->setArgument('roles', $role);
//        }
////
////        if (!in_array($input->getArgument('roles'), $rolesOptionKeys) ) {
////            while (in_array($input->getArgument('roles'), $rolesOptionKeys)){
////
////                $role = $this->getHelper('dialog')->askAndValidate(
////                    $output,
////                    'Please enter valid role [ROLE_USER, ROLE_ADMIN, ROLE_DUOCMS_EDITOR, ROLE_DUOCMS_ADMIN]:',
////                    function($role)
////                    {
////                        if (!empty($role)) {
////                            $roles[] = $role;
////                            return $roles;
////                        }
////                    }
////                );
////                $input->setArgument('roles', $role);
////            }
////        }
//
//        if ($input->getArgument('roles') == 'ROLE_USER') {
//            $output->writeln('ROLES IS EQUAL ROLE_USER');
//
//            $i = 1;
//
//            while (!in_array($input->getArgument('roles'), $rolesOptionKeys) || $i < $count_roles){
//                $output->writeln($i);
//                $output->writeln($count_roles);
//
//                $role = $this->getHelper('dialog')->askAndValidate(
//                    $output,
//                    'Please enter another role:',
//                    function($role) use ($count_roles, $i)
//                    {
//                        $roles[] = array();
//
//                        if($i < $count_roles) {
//                            return $roles;
//                        }
//                        if (!empty($role)) {
//                            $roles[] = $role;
//                            return $roles;
//                        }
//                    }
//                );
//                $input->setArgument('roles', $role);
//                $i++;
//            }
//        }
//
//        if ( $input->getArgument('roles') != 'ROLE_USER' ) {
//
//            while (!in_array($input->getArgument('roles'), $rolesOptionKeys)){
//                $output->writeln($input->getArgument('roles'));
//                $output->writeln(sprintf('%b',in_array($input->getArgument('roles'), $rolesOptionKeys)));
//                foreach ($rolesOptionKeys as $m) {
//                    $output->writeln($m);
//                }
//
//                $role = $this->getHelper('dialog')->askAndValidate(
//                    $output,
//                    'Please enter a valid role [ROLE_USER, ROLE_ADMIN, ROLE_DUOCMS_EDITOR, ROLE_DUOCMS_ADMIN]:',
//                    function($role)
//                    {
//                        if (!empty($role)) {
//                            $roles[] = $role;
//                            return $roles;
//                        }
//                    }
//                );
//                $input->setArgument('roles', $role);
//            }
//        }
    }
}
