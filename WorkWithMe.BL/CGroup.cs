﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL;






namespace WorkWithMe.BL
{

   public class CGroup
    {
        public Guid Id { get; set; }
        public string Description { get; set; }
        public string Name { get; set;  }
        public string GroupType { get; set; }
        public Guid OwnerUserId { get; set; }
        public Guid? OwnerGroupId { get; set; }
        public bool CanPostDefault { get; set; }
        public bool CanInviteDefault { get; set; }
        public bool CanDeleteDefault { get; set; }

        public int? GroupImgId { get; set; }

        public CGroup() { }

        public CGroup(Guid id) { Id = id;  }

        public CGroup(Guid id, string name, string description, string grouptype, Guid owneruserid, Guid? ownergroupid, bool canpostdefault, bool caninvitedefault, bool candeletedefault, int? groupimgid)
        {
            Id = id;
            Name = name;
            Description = description;
            GroupType = grouptype;
            OwnerUserId = owneruserid;
            OwnerGroupId = ownergroupid;
            CanPostDefault = canpostdefault;
            CanInviteDefault = caninvitedefault;
            CanDeleteDefault = candeletedefault;
            GroupImgId = groupimgid;
        }

        public CGroup(string name, string description, string grouptype, Guid owneruserid, Guid? ownergroupid, bool canpostdefault, bool caninvitedefault, bool candeletedefault, int? groupimgid)
        {
            Name = name;
            Description = description;
            GroupType = grouptype;
            OwnerUserId = owneruserid;
            OwnerGroupId = ownergroupid;
            CanPostDefault = canpostdefault;
            CanInviteDefault = caninvitedefault;
            CanDeleteDefault = candeletedefault;
            GroupImgId = groupimgid;
        }

        public void Create()
        {
            try
            {
                WorkWithMeDataContext oDC = new WorkWithMeDataContext();

                tblGroup g = new tblGroup();
                g.Id = Id;
                g.Name = Name;
                g.Description = Description;
                g.GroupType = GroupType;
                g.OwnerGroupId = OwnerGroupId;
                g.CanPostDefault = CanPostDefault;
                g.CanInviteDefault = CanInviteDefault;
                g.CanDeleteDefault = CanDeleteDefault;
                g.GroupImgId = GroupImgId;
               

                oDC.tblGroups.InsertOnSubmit(g);
                oDC.SubmitChanges();

            }
            catch (Exception ex)
            {
                throw ex;
            }
        }

         public void Update()
         {
             using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
             {
                 tblGroup group = (from g in oDC.tblGroups where g.Id == Id select g).FirstOrDefault();

                 group.Id = Id;
                 group.Name = Name;
                 group.Description = Description;
                 group.GroupType = GroupType;
                 group.OwnerGroupId = OwnerGroupId;
                 group.CanPostDefault = CanPostDefault;
                 group.CanInviteDefault = CanInviteDefault;
                 group.CanDeleteDefault = CanDeleteDefault;
                 group.GroupImgId = GroupImgId;

                 oDC.SubmitChanges();
             }
         } 

        public void Delete()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblGroup group = (from g in oDC.tblGroups where g.Id == Id select g).FirstOrDefault();

                oDC.tblGroups.DeleteOnSubmit(group);
                oDC.SubmitChanges();
            }
        }

    }
}
