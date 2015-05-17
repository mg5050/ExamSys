import os
import sys
import importlib

student = str(sys.argv[1])
exam = str(sys.argv[2])
db_name = student + '_' + exam
module_name = db_name
file_name = module_name + '.py'
#print ("db name: " + db_name)

# MySQL
def query(text):
	#print "\nQUERY: "+text
	return curs.execute(text)
def queryResult():
	return curs.fetchone()
def queryResults():
	return curs.fetchall()

import mysql.connector
from mysql.connector import errorcode

curs = 0
try:
	con = mysql.connector.connect(user='mg254', password='lineage82', host='sql2.njit.edu', database='mg254')
	curs = con.cursor(buffered=True)
except mysql.connector.Error as err:
  if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
    print("Something is wrong with your user name or password")
  elif err.errno == errorcode.ER_BAD_DB_ERROR:
    print("Database does not exist")
  else:
    print(err)
# End

# Steps:
# Grab user code, test vals, expected vals (MySQL)
# Send code to file
# Test file
# Delete file
# Output

user_ans = ''
test_val = []
test_out = []
prob_score = 0
tot_score = 0
usr_score = 0
query_txt = 'SELECT usr_qans, qarg, qans, qscore FROM ' + db_name + ',' + exam
query_txt = query_txt + ', questions WHERE ' + db_name + '.qid=questions.qid'
query_txt = query_txt + ' AND questions.qid=' + exam + '.qid;'
#print(query_txt)
query(query_txt)
rows = queryResults()

for row in rows: # for each question
	#print("Row: "+str(row))
	user_ans = row[0] # usr_qans
	test_val = row[1].split(",") # qarg
	test_out = row[2].split(",") # qans
	prob_score = row[3] # qscore
	#print("usr ans: " + str(user_ans) + "test values: " + str(test_val))
	#print("correct ans: " + str(test_out) + "max score: " + str(prob_score))

	if not row[1]: #trivia question
		print("Trivia:<br>")
		print('Correct answer: ' + str(test_out[0]) + '<br>Your answer: ' + str(user_ans))
		if(str(user_ans).lower() == str(test_out[0]).lower()): # right answer
			tot_score += int(prob_score)
			print('<br>Max score: ' + str(prob_score) + ' | Actual score: ' + str(prob_score) + "<br><br>")
		else: 
			print('<br>Max score: ' + str(prob_score) + ' | Actual score: 0<br><br>')
		continue


	f = open(file_name,'w')
	f.write(user_ans) # python will convert \n to os.linesep
	f.close() # you can omit in most cases as the destructor will call if

	print("Function:<br><br>" + str(user_ans) + "<br><br>")

	func_name = 'func'
	test_func = 0
	usr_py = importlib.import_module(module_name)
	importlib.reload(usr_py)
	test_func = getattr(usr_py, func_name) # implement a sanity check

	act_score = 0
	i = 0
	match = True
	for x in test_val:
		result = test_func(int(x))
		print('Input: ' + str(x) + ' | Expected output: ' + str(test_out[i]) + " | Actual output: " + str(result) + "<br>")
		if(str(result) != str(test_out[i])): # wrong answer
			match = False
		else:
			act_score += 1
		i += 1
	if(match): print("<br>Function passed.")
	else: 
		print("<br>Function failed one or more test cases.")
	tot_score += int(act_score)

	print('<br>Max score: ' + str(prob_score) + ' | Actual score: ' + str(act_score) + "<br><br>")

	os.remove(file_name)
print('<br><br><br>Total score: ' + str(tot_score) + "pt(s)")
print("<br><br><br>")