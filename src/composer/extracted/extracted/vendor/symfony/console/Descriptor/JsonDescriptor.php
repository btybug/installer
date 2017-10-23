<?php


namespace Symfony\Component\Console\Descriptor;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;


class JsonDescriptor extends Descriptor
{


    protected function describeInputArgument(InputArgument $argument, array $options = array())
    {
        $this->writeData($this->getInputArgumentData($argument), $options);
    }

    private function writeData(array $data, array $options)
    {
        $this->write(json_encode($data, isset($options['json_encoding']) ? $options['json_encoding'] : 0));
    }

    private function getInputArgumentData(InputArgument $argument)
    {
        return array(
            'name' => $argument->getName(),
            'is_required' => $argument->isRequired(),
            'is_array' => $argument->isArray(),
            'description' => preg_replace('/\s*[\r\n]\s*/', ' ', $argument->getDescription()),
            'default' => $argument->getDefault(),
        );
    }

    protected function describeInputOption(InputOption $option, array $options = array())
    {
        $this->writeData($this->getInputOptionData($option), $options);
    }

    private function getInputOptionData(InputOption $option)
    {
        return array(
            'name' => '--' . $option->getName(),
            'shortcut' => $option->getShortcut() ? '-' . implode('|-', explode('|', $option->getShortcut())) : '',
            'accept_value' => $option->acceptValue(),
            'is_value_required' => $option->isValueRequired(),
            'is_multiple' => $option->isArray(),
            'description' => preg_replace('/\s*[\r\n]\s*/', ' ', $option->getDescription()),
            'default' => $option->getDefault(),
        );
    }

    protected function describeInputDefinition(InputDefinition $definition, array $options = array())
    {
        $this->writeData($this->getInputDefinitionData($definition), $options);
    }

    private function getInputDefinitionData(InputDefinition $definition)
    {
        $inputArguments = array();
        foreach ($definition->getArguments() as $name => $argument) {
            $inputArguments[$name] = $this->getInputArgumentData($argument);
        }

        $inputOptions = array();
        foreach ($definition->getOptions() as $name => $option) {
            $inputOptions[$name] = $this->getInputOptionData($option);
        }

        return array('arguments' => $inputArguments, 'options' => $inputOptions);
    }

    protected function describeCommand(Command $command, array $options = array())
    {
        $this->writeData($this->getCommandData($command), $options);
    }

    private function getCommandData(Command $command)
    {
        $command->getSynopsis();
        $command->mergeApplicationDefinition(false);

        return array(
            'name' => $command->getName(),
            'usage' => array_merge(array($command->getSynopsis()), $command->getUsages(), $command->getAliases()),
            'description' => $command->getDescription(),
            'help' => $command->getProcessedHelp(),
            'definition' => $this->getInputDefinitionData($command->getNativeDefinition()),
        );
    }

    protected function describeApplication(Application $application, array $options = array())
    {
        $describedNamespace = isset($options['namespace']) ? $options['namespace'] : null;
        $description = new ApplicationDescription($application, $describedNamespace);
        $commands = array();

        foreach ($description->getCommands() as $command) {
            $commands[] = $this->getCommandData($command);
        }

        $data = $describedNamespace
            ? array('commands' => $commands, 'namespace' => $describedNamespace)
            : array('commands' => $commands, 'namespaces' => array_values($description->getNamespaces()));

        $this->writeData($data, $options);
    }
}
