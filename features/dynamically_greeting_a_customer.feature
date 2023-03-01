@greeting_customer @javascript
Feature: Dynamically greeting a customer
    In order to provide an ultimate customer experience
    As a Store Owner
    I want to welcome new customers dynamically

    Scenario: Dynamically greeting a customer with an unknown name
        When a customer with an unknown name visits dynamic welcome page
        Then they should be dynamically greeted with "Lemisoft Boilerplate!"

    Scenario: Dynamically greeting a customer with a known name
        When a customer named "Krzysztof" visits dynamic welcome page
        Then they should be dynamically greeted with "Lemisoft Boilerplate, Krzysztof!"
