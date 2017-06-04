# uk.co.mjwconsult.birthyear

Adds a custom field to hold the year of birth.

Synchronises a custom field "birth_year" with the contact birth_date field according to the following rules:
  1. Fills the custom field with the correct birth year if someone updates CiviCRM's Birth Date field.
  2. Deletes the value CiviCRM's Birth Date field if someone updates the custom field with a year that is different to the birth date.
