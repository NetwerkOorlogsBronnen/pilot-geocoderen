import sys
import json

#print sys.argv[1]
import HTMLParser
pars = HTMLParser.HTMLParser()

sentences = []

from pattern.nl import parse, split
sentence = pars.unescape(sys.argv[1])

s = parse(sentence)
for sentence in split(s):
	#print type(sentence)
	sentences.append(str(sentence))

print json.dumps(sentences)