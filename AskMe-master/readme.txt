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

