1. changes: new user only has password

11/5:
1. new user can register & user can login

11/6:
USER
====
display all answers related to the question:

select * from replies where question_id = ?


DOC:
====
answer question query:
insert into replies (doctor_id, question_id, reply) values (?, ?, ?)

like an answer:
select likes from replies where doctor_id = ?
update replies set likes = ? where doctor_id = ?


Sample data:
===
1. user
2. questions

INSERT INTO questions (title, questions, date, age, height, weight, gender, smoke, alcohol, drugs, allergies, others) VALUES ('type 2 diabetes','Will a person with Type 2 diabetes under control end up with the need for insulin?', '2016-11-06 04:06:15', 35, 178, 150, 'male', 'yes', 'no', 'no', 'NA', 'NA' );


3. follow
INSERT INTO follow (question_id, user_id) VALUES (1, 1);

