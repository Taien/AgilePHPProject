﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.ServiceModel;
using System.ServiceModel.Web;
using System.Text;
using WorkWithMe.BL;

namespace WorkWithMe.SL
{
    // NOTE: You can use the "Rename" command on the "Refactor" menu to change the class name "Service1" in code, svc and config file together.
    // NOTE: In order to launch WCF Test Client for testing this service, please select Service1.svc or Service1.svc.cs at the Solution Explorer and start debugging.
    public class WorkWithMeService : IWorkWithMeService
    {
      
        public bool CreateUser(string username, string password, string firstName, string middleInitial, string lastName, 
                               int? zip, string address, string city, string state, bool isAddressPrivate, ref string response)
        {
            CUser newUser = new CUser(username, firstName, middleInitial, lastName, zip, address, isAddressPrivate);

            return newUser.Create(password, city, state, ref response);
        }

        public CUser DoLogin(string username, string password)
        {
            CUser user = new CUser();
            if (user.Login(username, password)) return user;
            else return null;
        }

        public bool CreatePost(Guid posterId, Guid? targetGroupId, string title, string content, bool isSticky, DateTime eventTimeStamp)
        {
            return false;
        }
    }
}
