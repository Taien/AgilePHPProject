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

        [OperationContract]
        CUserList SearchUser(string searchString);
    }
}
