# Psr3Decorator
A decorator object that can be used to add enhanced functionality to any Psr3 implementation

This project is a work in progress, but the general idea is to declare your own logger class with custom
functionality that can be added to any Psr3 implementation.

You can add your own functionality but writing methods into this class, as well as adding pre-defined functionality by 
importing any traits that you need.  Once you have your custom class built the way you want it, you can instantiate it
by passing a Psr3 implementation into the constructor.

In the repo, you can see an example of this in 'MyLogger.php' and 'test.php'.


