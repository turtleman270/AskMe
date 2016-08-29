//
//  animals.playground
//  iOS Networking
//
//  Created by Jarrod Parkes on 09/30/15.
//  Copyright (c) 2015 Udacity. All rights reserved.
//

import Foundation

/* Path for JSON files bundled with the Playground */
var pathForAnimalsJSON = NSBundle.mainBundle().pathForResource("animals", ofType: "json")

/* Raw JSON data (...simliar to the format you might receive from the network) */
var rawAnimalsJSON = NSData(contentsOfFile: pathForAnimalsJSON!)

/* Error object */
var parsingAnimalsError: NSError? = nil

/* Parse the data into usable form */
var parsedAnimalsJSON = try! NSJSONSerialization.JSONObjectWithData(rawAnimalsJSON!, options: .AllowFragments) as! NSDictionary

func parseJSONAsDictionary(dictionary: NSDictionary){
    /* Start playing with JSON here... */
    let photos = dictionary["photos"] as! [String: AnyObject]
    let photo = photos["photo"] as! [[String: AnyObject]]
    let url = photo[2]["url_m"] as! String
    
    let imageURL = NSURL(string: url)
    if let imageData = NSData(contentsOfURL: imageURL!) {
        
    }

//    for i in 0...photo.count {
//        let content = photo[i]["comment"]!["_content"] as! String
//        if content.containsString("interrufftion") {
//            return i
//        }
//    }
//    return -1
}

parseJSONAsDictionary(parsedAnimalsJSON)

