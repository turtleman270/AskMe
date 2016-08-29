//: Playground - noun: a place where people can play

import UIKit
import XCPlayground

// this line tells the Playground to execute indefinitely
XCPlaygroundPage.currentPage.needsIndefiniteExecution = true

let urlString = "http://quotes.rest/qod.json?category=inspire"
let url = NSURL(string: urlString)
let request = NSMutableURLRequest(URL: url!)
let session = NSURLSession.sharedSession()
let task = session.dataTaskWithRequest(request) { data, response, error in
    if error != nil { // Handle error
        return
    }
    print(NSString(data: data!, encoding: NSUTF8StringEncoding))
}
task.resume()
