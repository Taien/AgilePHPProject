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
        bool CreatePost(string posterId, string targetGroupId, string title, string content, bool isSticky, DateTime? eventTimeStamp);

        [OperationContract]
        CUser DoLogin(string username, string password);

        [OperationContract]
        bool CreateUser(string username, string password, string firstName, string middleInitial, string lastName, int? zip, string address, string city, string state, bool isAddressPrivate, string email, ref string response);

        [OperationContract]
        bool UpdateUser(string id, string username, string password, string firstName, string middleInitial, string lastName, int? zip, string address, string city, string state, bool isAddressPrivate, string email, ref string response);

        [OperationContract]
        CPostList GetPostsForUser(string userId);

        [OperationContract]
        CCityStateInfo GetCityStateInfo(int zip);

        [OperationContract]
        void CreateCity(string name);

        [OperationContract]
        void UpdateCity(string id, string name);

        [OperationContract]
        void DeleteCity(string id);

      
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
        void UpdateGroupType(string id, string description);

        [OperationContract]
        void DeleteGroupType(string id);

        [OperationContract]
        void CreateInviteStatus(string description);

        [OperationContract]
        void UpdateInviteStatus(string id, string description);

        [OperationContract]
        void DeleteInviteStatus(string id);

        [OperationContract]
        void DeletePost(string posterid, string targetgroupid);

        [OperationContract]
        void CreateState(string id, string statename);

        [OperationContract]
        void UpdateState(string statename);

        [OperationContract]
        void DeleteState(string id);

    }
}
