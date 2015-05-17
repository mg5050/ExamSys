import time, os, fnmatch, re, sys

path = "../"
stop_file = path+"stop.daemon"
user = str(sys.argv[1])
pattern = user+"_*.exam"
exam_regex = user+"_(.*).exam"

while True:
	for exam in os.listdir(path): # list all files in path
		if fnmatch.fnmatch(exam, pattern): # if its a match to pattern
			print(exam)
			os.remove(path+exam) # remvoe file
			m = re.search(exam_regex, exam) # grab exam part only
			print("Exams:", m.group(1)) # exam name
			cmd = "~/bin/python grade.py " + user + " " + m.group(1) + " > ~/public_html/ubd2_" + m.group(1) + ".graded"
			print(cmd)
			os.system(cmd) # run grading on exam
	if(os.path.isfile(stop_file)): # stop file created
		os.remove(stop_file)
		print("Stopping daemon...")
		break
	time.sleep(10)