using System;
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
                               int? zip, string address, string city, string state, bool isAddressPrivate, string email, ref string response)
        {
            CUser newUser = new CUser(username, firstName, middleInitial, lastName, zip, address, isAddressPrivate, email);

            return newUser.Create(password, city, state, ref response);
        }

        public bool UpdateUser(string id, string username, string password, string firstName, string middleInitial, string lastName,
                               int? zip, string address, string city, string state, bool isAddressPrivate, string email, ref string response)
        {
            CUser user = new CUser(Guid.Parse(id), username, firstName, middleInitial, lastName, zip, address, isAddressPrivate, email);

            return user.Update(password, city, state, ref response);
        }
        
        public CUser DoLogin(string username, string password)
        {
            CUser user = new CUser();
            if (user.Login(username, password)) return user;
            else return null;
        }

        public bool CreatePost(string posterId, string targetGroupId, string title, string content, bool isSticky, DateTime? eventTimeStamp)
        {
            CPost post = new CPost(Guid.Parse(posterId), (targetGroupId == null) ? Guid.Empty : Guid.Parse(targetGroupId), title, content, isSticky, false, DateTime.Now, eventTimeStamp);
            if (post.Create() > 0) return true;
            return false;
        }

       
        public CPostList GetPostsForUser(string userId)
        {
            CPostList list = new CPostList();
            list.LoadPostsForUser(Guid.Parse(userId));
            return list;
        }
        
        public CCityStateInfo GetCityStateInfo(int zip)
        {
            CCityStateInfo info = new CCityStateInfo(zip);
            info.GetInfo();
            return info;
        }
        
        public void CreateCity(string name)
        {
            CCity city = new CCity(name);
            city.Create();
        }
        
        public void UpdateCity(string id, string name)
        {
            CCity city = new CCity(Guid.Parse(id), name);
            city.Update();
        }
        
        public void DeleteCity(string id)
        {
            CCity city = new CCity(Guid.Parse(id));
            city.Delete();
        }

        /////////////maggie started here

       public void CreateGroup(string name, string description, string grouptype, string owneruserid, string ownergroupid, bool canpostdefault, bool caninvitedefault, bool candeletedefault)
        {
            CGroup group = new CGroup(Guid.Parse(name), description, grouptype, Guid.Parse(owneruserid), Guid.Parse(ownergroupid), canpostdefault, caninvitedefault, candeletedefault);
            group.Create(); 
        }

       public void UpdateGroup(string id, string description, string grouptype, string owneruserid, string ownergroupid, bool canpostdefault, bool caninvitedefault, bool candeletedefault)
        {
            CGroup group = new CGroup(Guid.Parse(id), description, grouptype, Guid.Parse(owneruserid), Guid.Parse(ownergroupid), canpostdefault, caninvitedefault, candeletedefault);
            group.Update(); 
        }

        public void DeleteGroup(string id)
        {
            CGroup group = new CGroup(Guid.Parse(id));
            group.Delete(); 
        }

        public void CreateGroupType( string description)
        {
            CGroupType grouptype = new CGroupType(description);
            grouptype.Create(); 
        }
        
        public void UpdateGroupType(int id, string description)
        {
            CGroupType grouptype = new CGroupType(id, description);
            grouptype.Update(); 
        }

        public void DeleteGroupType(int id)
        {
            CGroupType grouptype = new CGroupType(id);
            grouptype.Delete();
        }

        public void CreateInviteStatus(string description)
        {
            CInviteStatus invitestatus = new CInviteStatus(description);
            invitestatus.Create(); 
        }

        public void UpdateInviteStatus(int id, string description)
        {
            CInviteStatus invitestatus = new CInviteStatus(id, description);
            invitestatus.Update(); 
        }

        public void DeleteInviteStatus(int id)
        {
            CInviteStatus invitestatus = new CInviteStatus(id);
            invitestatus.Delete(); 
        }

        public void DeletePost(string id)
        {
            CPost post = new CPost(Guid.Parse(id));
            post.Delete();
        }

        public void CreateState(string statename)
        {
            CState state = new CState(statename);
            state.Create(); 
        }

        public void UpdateState(string id, string statename)
        {
            CState state = new CState(Guid.Parse(id), statename);
            state.Update();
        }

        public void DeleteState(string id)
        {
            CState state = new CState(Guid.Parse(id));
            state.Delete(); 
        }
        

        public CUserList SearchUser(string searchString)
        {
            CUserList results = new CUserList();
            results.SearchForUser(searchString);
            return results;
        }
    }
}
