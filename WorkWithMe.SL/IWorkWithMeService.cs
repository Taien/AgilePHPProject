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
    // NOTE: You can use the "Rename" command on the "Refactor" menu to change the interface name "IService1" in both code and config file together.
    [ServiceContract]
    public interface IWorkWithMeService
    {
        [OperationContract]
        bool CreatePost(string posterId, string targetGroupId, string replyPostId, string title, string content, bool isSticky, DateTime? eventTimeStamp);

        [OperationContract]
        CUser DoLogin(string username, string password);

        [OperationContract]
        bool CreateUser(string username, string password, string firstName, string middleInitial, string lastName, int? zip, string address, string city, string state, bool isAddressPrivate, string email, ref string response);

        [OperationContract]
        bool UpdateUser(string id, string username, string password, string firstName, string middleInitial, string lastName, int? zip, string address, string city, string state, bool isAddressPrivate, string email, ref string response);

        [OperationContract]
        bool UpdateUserWithImage(string id, string username, string password, string firstName, string middleInitial, string lastName, int? zip, string address, string city, string state, bool isAddressPrivate, string email, string imageName, string imageSize, byte[] imageContent, ref string response);

        [OperationContract]
        CPostList GetPostsForUser(string userId);

        [OperationContract]
        CPostList GetOffsetPostsForUser(string userId, int offset);

        [OperationContract]
        CPostList GetRepliesForPost(string postId);

        [OperationContract]
        CCityStateInfo GetCityStateInfo(int zip);

        [OperationContract]
        void CreateCity(string name);

        [OperationContract]
        void UpdateCity(string id, string name);

        [OperationContract]
        void DeleteCity(string id);

        [OperationContract]
        CUser GetUser(string id);
      
        /////////////////////where maggie started some mayhem 
        [OperationContract]
        void CreateGroup(string name, string description, string grouptype, string owneruserid, string ownergroupid, bool canpostdefault, bool caninvitedefault, bool candeletedefault);
        
        [OperationContract]
        void UpdateGroup(string id, string name, string description, string grouptype, string owneruserid, string ownergroupid, bool canpostdefault, bool caninvitedefault, bool candeletedefault);

        [OperationContract]
        void DeleteGroup(string id);

        [OperationContract]
        void CreateGroupType(string description);

        [OperationContract]
        void UpdateGroupType(int id, string description);

        [OperationContract]
        void DeleteGroupType(int id);

        [OperationContract]
        void CreateInviteStatus(string description);

        [OperationContract]
        void UpdateInviteStatus(int id, string description);

        [OperationContract]
        void DeleteInviteStatus(int id);

        [OperationContract]
        void DeletePost(string id);

        [OperationContract]
        void CreateState(string stateName);

        [OperationContract]
        void UpdateState(string id, string stateName);

        [OperationContract]
        void DeleteState(string id);

        [OperationContract]
        void CreateUserContact(string originUserId, string targetUserId, int inviteStatusId);

        [OperationContract]
        void UpdateUserContact(string id, string originUserId, string targetUserId, int inviteStatusId);

        [OperationContract]
        void DeleteUserContact(string id);

        [OperationContract]
        CUserList SearchForUser(string searchString, string originUserId);

        [OperationContract]
        CUserContactList LoadContactsForUser(string id);

        [OperationContract]
        CUserContactList LoadInvitesForUser(string id);
        
    }
}
