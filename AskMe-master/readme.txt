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

4. doctor:

INSERT INTO doctors (name, password, gender) VALUES ('Saul Metzstein', '$1$B2qPMLtn$7eGOq.N.JUYQUW2vNtECE.', 'male');


5. answers:

INSERT INTO replies (doctor_id, question_id,reply) VALUES (1, 5,'As you may have read, Type 2 diabetes is a progressive disease. Will you require insulin? That all depends on individual factors that includes, among many other factors, weight, exercise, genetics, hormones and beta-cells, those cells that produce insulin in your pancreas. Research shows that managing your diabetes early in the disease process can have big payoffs in later years. Joining a support group for people with diabetes can be helpful in keeping you going in your health quest. Following up with your health care team regularly and keeping abreast on the new developments in diabetes management can also benefit you.' );
